<?php

namespace App\Services;

use App\Models\DashboardWidget;
use App\Models\DashboardConfig;
use App\Models\WidgetCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WidgetService
{
    /**
     * Cache TTL em minutos
     */
    const CACHE_TTL = 30;
    
    /**
     * Tipos de widgets disponíveis
     */
    const WIDGET_TYPES = [
        'financial-summary' => [
            'name' => 'Resumo Financeiro',
            'description' => 'Visão geral das finanças',
            'icon' => 'dollar-sign',
            'category' => 'financeiro',
            'sizes' => ['S', 'M', 'L'],
            'default_size' => 'M'
        ],
        'budgets' => [
            'name' => 'Orçamentos',
            'description' => 'Status dos orçamentos ativos',
            'icon' => 'file-text',
            'category' => 'projetos',
            'sizes' => ['M', 'L'],
            'default_size' => 'M'
        ],
        'upcoming-deadlines' => [
            'name' => 'Prazos Próximos',
            'description' => 'Tarefas e compromissos urgentes',
            'icon' => 'clock',
            'category' => 'produtividade',
            'sizes' => ['S', 'M', 'L'],
            'default_size' => 'M'
        ],
        'pipeline' => [
            'name' => 'Pipeline de Vendas',
            'description' => 'Status dos orçamentos em andamento',
            'icon' => 'trending-up',
            'category' => 'projetos',
            'sizes' => ['M', 'L'],
            'default_size' => 'L'
        ],
        'notifications' => [
            'name' => 'Notificações',
            'description' => 'Alertas e avisos importantes',
            'icon' => 'bell',
            'category' => 'produtividade',
            'sizes' => ['S', 'M'],
            'default_size' => 'S'
        ],
        'top-clients' => [
            'name' => 'Top Clientes',
            'description' => 'Clientes mais importantes',
            'icon' => 'users',
            'category' => 'projetos',
            'sizes' => ['M', 'L'],
            'default_size' => 'M'
        ],
        'shortcuts' => [
            'name' => 'Atalhos',
            'description' => 'Acesso rápido às funcionalidades',
            'icon' => 'zap',
            'category' => 'produtividade',
            'sizes' => ['S', 'M', 'L'],
            'default_size' => 'M'
        ],
        'recent-activity' => [
            'name' => 'Atividade Recente',
            'description' => 'Últimas ações do sistema',
            'icon' => 'activity',
            'category' => 'produtividade',
            'sizes' => ['M', 'L'],
            'default_size' => 'M'
        ],
        'portfolio-metrics' => [
            'name' => 'Métricas do Portfólio',
            'description' => 'Estatísticas dos projetos',
            'icon' => 'bar-chart-3',
            'category' => 'projetos',
            'sizes' => ['S', 'M', 'L'],
            'default_size' => 'L'
        ]
    ];
    
    /**
     * Obter widgets do usuário
     */
    public function getUserWidgets($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        return DashboardWidget::where('user_id', $userId)
            ->orderBy('position')
            ->orderBy('created_at')
            ->get()
            ->map(function ($widget) {
                return $this->formatWidget($widget);
            });
    }
    
    /**
     * Obter widgets disponíveis para adicionar
     */
    public function getAvailableWidgets($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        // Widgets já adicionados pelo usuário
        $userWidgetTypes = DashboardWidget::where('user_id', $userId)
            ->pluck('widget_type')
            ->toArray();
        
        // Filtrar widgets disponíveis
        $available = [];
        foreach (self::WIDGET_TYPES as $type => $config) {
            if (!in_array($type, $userWidgetTypes)) {
                $available[$type] = $config;
            }
        }
        
        // Agrupar por categoria
        $grouped = [];
        foreach ($available as $type => $config) {
            $category = $config['category'];
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][$type] = $config;
        }
        
        return $grouped;
    }
    
    /**
     * Adicionar widget para o usuário
     */
    public function addWidget($widgetType, $size = null, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        // Verificar se o tipo de widget é válido
        if (!isset(self::WIDGET_TYPES[$widgetType])) {
            throw new \InvalidArgumentException("Tipo de widget inválido: {$widgetType}");
        }
        
        $config = self::WIDGET_TYPES[$widgetType];
        $size = $size ?? $config['default_size'];
        
        // Verificar se o tamanho é válido
        if (!in_array($size, $config['sizes'])) {
            throw new \InvalidArgumentException("Tamanho inválido para o widget {$widgetType}: {$size}");
        }
        
        // Verificar se o usuário já tem este widget
        $exists = DashboardWidget::where('user_id', $userId)
            ->where('widget_type', $widgetType)
            ->exists();
        
        if ($exists) {
            throw new \InvalidArgumentException("Widget {$widgetType} já foi adicionado");
        }
        
        // Obter próxima posição
        $nextPosition = DashboardWidget::where('user_id', $userId)->max('position') + 1;
        
        // Criar widget
        $widget = DashboardWidget::create([
            'user_id' => $userId,
            'widget_type' => $widgetType,
            'title' => $config['name'],
            'size' => $size,
            'position' => $nextPosition,
            'is_pinned' => false,
            'is_visible' => true,
            'settings' => []
        ]);
        
        // Limpar cache
        $this->clearWidgetCache($userId, $widgetType);
        
        return $this->formatWidget($widget);
    }
    
    /**
     * Atualizar widget
     */
    public function updateWidget($widgetId, $data, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $widget = DashboardWidget::where('id', $widgetId)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        // Validar tamanho se fornecido
        if (isset($data['size'])) {
            $config = self::WIDGET_TYPES[$widget->widget_type];
            if (!in_array($data['size'], $config['sizes'])) {
                throw new \InvalidArgumentException("Tamanho inválido: {$data['size']}");
            }
        }
        
        // Atualizar campos permitidos
        $allowedFields = ['title', 'size', 'is_pinned', 'is_visible', 'settings'];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $widget->$field = $data[$field];
            }
        }
        
        $widget->save();
        
        // Limpar cache
        $this->clearWidgetCache($userId, $widget->widget_type);
        
        return $this->formatWidget($widget);
    }
    
    /**
     * Remover widget
     */
    public function removeWidget($widgetId, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $widget = DashboardWidget::where('id', $widgetId)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        $widgetType = $widget->widget_type;
        
        $widget->delete();
        
        // Reordenar posições
        $this->reorderWidgets($userId);
        
        // Limpar cache
        $this->clearWidgetCache($userId, $widgetType);
        
        return true;
    }
    
    /**
     * Atualizar posições dos widgets
     */
    public function updateWidgetPositions($positions, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        DB::transaction(function () use ($positions, $userId) {
            foreach ($positions as $position => $widgetId) {
                DashboardWidget::where('id', $widgetId)
                    ->where('user_id', $userId)
                    ->update(['position' => $position + 1]);
            }
        });
        
        // Limpar cache de todos os widgets do usuário
        $this->clearUserWidgetCache($userId);
        
        return true;
    }
    
    /**
     * Obter dados do widget com cache
     */
    public function getWidgetData($widgetType, $userId = null, $forceRefresh = false)
    {
        $userId = $userId ?? Auth::id();
        $cacheKey = "widget_data_{$userId}_{$widgetType}";
        
        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }
        
        return Cache::remember($cacheKey, self::CACHE_TTL * 60, function () use ($widgetType, $userId) {
            return $this->generateWidgetData($widgetType, $userId);
        });
    }
    
    /**
     * Gerar dados do widget
     */
    protected function generateWidgetData($widgetType, $userId)
    {
        switch ($widgetType) {
            case 'financial-summary':
                return $this->getFinancialSummaryData($userId);
            case 'budgets':
                return $this->getBudgetsData($userId);
            case 'upcoming-deadlines':
                return $this->getUpcomingDeadlinesData($userId);
            case 'pipeline':
                return $this->getPipelineData($userId);
            case 'notifications':
                return $this->getNotificationsData($userId);
            case 'top-clients':
                return $this->getTopClientsData($userId);
            case 'shortcuts':
                return $this->getShortcutsData($userId);
            case 'recent-activity':
                return $this->getRecentActivityData($userId);
            case 'portfolio-metrics':
                return $this->getPortfolioMetricsData($userId);
            default:
                return [];
        }
    }
    
    /**
     * Formatar widget para resposta
     */
    protected function formatWidget($widget)
    {
        $config = self::WIDGET_TYPES[$widget->widget_type] ?? [];
        
        return [
            'id' => $widget->id,
            'widget_type' => $widget->widget_type,
            'title' => $widget->title,
            'size' => $widget->size,
            'position' => $widget->position,
            'is_pinned' => $widget->is_pinned,
            'is_visible' => $widget->is_visible,
            'settings' => $widget->settings,
            'config' => $config,
            'data' => $this->getWidgetData($widget->widget_type, $widget->user_id),
            'updated_at' => $widget->updated_at->toISOString()
        ];
    }
    
    /**
     * Reordenar widgets após remoção
     */
    protected function reorderWidgets($userId)
    {
        $widgets = DashboardWidget::where('user_id', $userId)
            ->orderBy('position')
            ->get();
        
        foreach ($widgets as $index => $widget) {
            $widget->update(['position' => $index + 1]);
        }
    }
    
    /**
     * Limpar cache de widget específico
     */
    protected function clearWidgetCache($userId, $widgetType)
    {
        $cacheKey = "widget_data_{$userId}_{$widgetType}";
        Cache::forget($cacheKey);
        
        // Também limpar cache do banco
        WidgetCache::where('user_id', $userId)
            ->where('widget_type', $widgetType)
            ->delete();
    }
    
    /**
     * Limpar cache de todos os widgets do usuário
     */
    protected function clearUserWidgetCache($userId)
    {
        foreach (array_keys(self::WIDGET_TYPES) as $widgetType) {
            $this->clearWidgetCache($userId, $widgetType);
        }
    }
    
    // Métodos para gerar dados específicos de cada widget
    // Estes métodos serão implementados com base nos dados reais do sistema
    
    protected function getFinancialSummaryData($userId)
    {
        // Implementar lógica para resumo financeiro
        return [
            'saldo_atual' => 0,
            'receitas_mes' => 0,
            'despesas_mes' => 0,
            'variacao_percentual' => 0
        ];
    }
    
    protected function getBudgetsData($userId)
    {
        // Implementar lógica para orçamentos
        return [
            'total_orcamentos' => 0,
            'valor_total' => 0,
            'orcamentos' => []
        ];
    }
    
    protected function getUpcomingDeadlinesData($userId)
    {
        // Implementar lógica para prazos próximos
        return [
            'prazos' => []
        ];
    }
    
    protected function getPipelineData($userId)
    {
        // Implementar lógica para pipeline
        return [
            'estagios' => [],
            'valor_total' => 0,
            'taxa_conversao' => 0
        ];
    }
    
    protected function getNotificationsData($userId)
    {
        // Implementar lógica para notificações
        return [
            'notificacoes' => []
        ];
    }
    
    protected function getTopClientsData($userId)
    {
        // Implementar lógica para top clientes
        return [
            'clientes' => []
        ];
    }
    
    protected function getShortcutsData($userId)
    {
        // Implementar lógica para atalhos
        return [
            'atalhos' => []
        ];
    }
    
    protected function getRecentActivityData($userId)
    {
        // Implementar lógica para atividade recente
        return [
            'atividades' => []
        ];
    }
    
    protected function getPortfolioMetricsData($userId)
    {
        // Implementar lógica para métricas do portfólio
        return [
            'metricas' => []
        ];
    }
}