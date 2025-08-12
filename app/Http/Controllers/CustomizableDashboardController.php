<?php

namespace App\Http\Controllers;

use App\Models\DashboardConfig;
use App\Models\DashboardWidget;
use App\Models\DashboardPreset;
use App\Models\PresetWidget;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomizableDashboardController extends Controller
{
    /**
     * Exibir o dashboard personalizável
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Buscar configuração do usuário ou criar uma padrão
        $config = DashboardConfig::firstOrCreate(
            ['user_id' => $user->id],
            [
                'layout' => ['columns' => 4, 'gap' => 16, 'padding' => 24],
                'widget_positions' => [],
                'pinned_widgets' => [],
                'is_default' => true
            ]
        );

        // Buscar widgets do usuário
        $widgets = DashboardWidget::where('user_id', $user->id)
            ->active()
            ->orderBy('position_y')
            ->orderBy('position_x')
            ->get();

        // Buscar presets disponíveis
        $presets = DashboardPreset::active()->system()->get();

        return view('admin.customizable-dashboard', compact('config', 'widgets', 'presets'));
    }

    /**
     * Obter configuração do dashboard do usuário
     */
    public function getConfig(): JsonResponse
    {
        $user = Auth::user();
        
        $config = DashboardConfig::where('user_id', $user->id)->first();
        
        if (!$config) {
            $config = DashboardConfig::create([
                'user_id' => $user->id,
                'layout' => ['columns' => 4, 'gap' => 16, 'padding' => 24],
                'widget_positions' => [],
                'pinned_widgets' => [],
                'is_default' => true
            ]);
        }

        return response()->json($config);
    }

    /**
     * Salvar configuração do dashboard
     */
    public function saveConfig(Request $request): JsonResponse
    {
        $request->validate([
            'layout' => 'required|array',
            'widget_positions' => 'required|array',
            'pinned_widgets' => 'nullable|array|max:3'
        ]);

        $user = Auth::user();
        
        $config = DashboardConfig::updateOrCreate(
            ['user_id' => $user->id],
            [
                'layout' => $request->layout,
                'widget_positions' => $request->widget_positions,
                'pinned_widgets' => $request->pinned_widgets ?? [],
                'is_default' => false
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Configuração salva com sucesso!',
            'config' => $config
        ]);
    }

    /**
     * Obter widgets do usuário
     */
    public function getWidgets(): JsonResponse
    {
        $user = Auth::user();
        
        $widgets = DashboardWidget::where('user_id', $user->id)
            ->active()
            ->orderBy('position_y')
            ->orderBy('position_x')
            ->get();

        return response()->json($widgets);
    }

    /**
     * Adicionar widget ao dashboard
     */
    public function addWidget(Request $request): JsonResponse
    {
        $request->validate([
            'widget_type' => 'required|string',
            'title' => 'required|string',
            'size' => 'required|in:S,M,L',
            'position_x' => 'required|integer|min:0',
            'position_y' => 'required|integer|min:0',
            'width' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
            'settings' => 'nullable|array'
        ]);

        $user = Auth::user();
        
        $widget = DashboardWidget::create([
            'user_id' => $user->id,
            'widget_type' => $request->widget_type,
            'title' => $request->title,
            'size' => $request->size,
            'position_x' => $request->position_x,
            'position_y' => $request->position_y,
            'width' => $request->width,
            'height' => $request->height,
            'is_pinned' => false,
            'settings' => $request->settings ?? [],
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Widget adicionado com sucesso!',
            'widget' => $widget
        ]);
    }

    /**
     * Atualizar widget
     */
    public function updateWidget(Request $request, DashboardWidget $widget): JsonResponse
    {
        // Verificar se o widget pertence ao usuário
        if ($widget->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string',
            'size' => 'sometimes|in:S,M,L',
            'position_x' => 'sometimes|integer|min:0',
            'position_y' => 'sometimes|integer|min:0',
            'width' => 'sometimes|integer|min:1',
            'height' => 'sometimes|integer|min:1',
            'is_pinned' => 'sometimes|boolean',
            'settings' => 'sometimes|array'
        ]);

        $widget->update($request->only([
            'title', 'size', 'position_x', 'position_y', 
            'width', 'height', 'is_pinned', 'settings'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Widget atualizado com sucesso!',
            'widget' => $widget
        ]);
    }

    /**
     * Remover widget
     */
    public function removeWidget(DashboardWidget $widget): JsonResponse
    {
        // Verificar se o widget pertence ao usuário
        if ($widget->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $widget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Widget removido com sucesso!'
        ]);
    }

    /**
     * Obter presets disponíveis
     */
    public function getPresets(): JsonResponse
    {
        $presets = DashboardPreset::active()
            ->system()
            ->with('widgets')
            ->get();

        return response()->json($presets);
    }

    /**
     * Aplicar preset ao dashboard do usuário
     */
    public function applyPreset(Request $request, DashboardPreset $preset): JsonResponse
    {
        $request->validate([
            'replace_existing' => 'boolean'
        ]);

        $user = Auth::user();
        $replaceExisting = $request->boolean('replace_existing', true);

        DB::transaction(function () use ($user, $preset, $replaceExisting) {
            // Se deve substituir widgets existentes
            if ($replaceExisting) {
                DashboardWidget::where('user_id', $user->id)->delete();
            }

            // Criar widgets do preset para o usuário
            foreach ($preset->widgets as $presetWidget) {
                DashboardWidget::create([
                    'user_id' => $user->id,
                    'widget_type' => $presetWidget->widget_type,
                    'title' => $presetWidget->title,
                    'size' => $presetWidget->size,
                    'position_x' => $presetWidget->position_x,
                    'position_y' => $presetWidget->position_y,
                    'width' => $presetWidget->width,
                    'height' => $presetWidget->height,
                    'is_pinned' => $presetWidget->is_pinned,
                    'settings' => $presetWidget->settings ?? [],
                    'is_active' => true
                ]);
            }

            // Atualizar configuração do dashboard
            DashboardConfig::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'layout' => $preset->layout_config,
                    'widget_positions' => [],
                    'pinned_widgets' => [],
                    'is_default' => false
                ]
            );
        });

        return response()->json([
            'success' => true,
            'message' => "Preset '{$preset->name}' aplicado com sucesso!"
        ]);
    }

    /**
     * Atualizar posições dos widgets em lote
     */
    public function updateWidgetPositions(Request $request): JsonResponse
    {
        $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|integer|exists:dashboard_widgets,id',
            'widgets.*.position_x' => 'required|integer|min:0',
            'widgets.*.position_y' => 'required|integer|min:0',
            'widgets.*.width' => 'required|integer|min:1',
            'widgets.*.height' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        
        DB::transaction(function () use ($request, $user) {
            foreach ($request->widgets as $widgetData) {
                DashboardWidget::where('id', $widgetData['id'])
                    ->where('user_id', $user->id)
                    ->update([
                        'position_x' => $widgetData['position_x'],
                        'position_y' => $widgetData['position_y'],
                        'width' => $widgetData['width'],
                        'height' => $widgetData['height']
                    ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Posições atualizadas com sucesso!'
        ]);
    }
}
