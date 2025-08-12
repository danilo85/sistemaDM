<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DashboardPreset;
use App\Models\PresetWidget;

class DashboardPresetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Preset 1: Produtividade
        $produtividade = DashboardPreset::create([
            'name' => 'Produtividade',
            'slug' => 'produtividade',
            'description' => 'Foco em tarefas, prazos e atividades recentes',
            'layout_config' => [
                'columns' => 4,
                'gap' => 16,
                'padding' => 24
            ],
            'icon' => 'zap',
            'is_system' => true,
            'is_active' => true
        ]);

        // Widgets do preset Produtividade
        $produtividadeWidgets = [
            ['widget_type' => 'upcoming_deadlines', 'title' => 'Prazos Próximos', 'size' => 'L', 'position_x' => 0, 'position_y' => 0, 'width' => 2, 'height' => 2, 'is_pinned' => true],
            ['widget_type' => 'recent_activity', 'title' => 'Atividade Recente', 'size' => 'M', 'position_x' => 2, 'position_y' => 0, 'width' => 2, 'height' => 1, 'is_pinned' => false],
            ['widget_type' => 'shortcuts', 'title' => 'Atalhos Rápidos', 'size' => 'M', 'position_x' => 2, 'position_y' => 1, 'width' => 2, 'height' => 1, 'is_pinned' => false],
            ['widget_type' => 'notifications', 'title' => 'Notificações', 'size' => 'S', 'position_x' => 0, 'position_y' => 2, 'width' => 1, 'height' => 1, 'is_pinned' => false],
            ['widget_type' => 'pipeline', 'title' => 'Pipeline de Projetos', 'size' => 'L', 'position_x' => 1, 'position_y' => 2, 'width' => 3, 'height' => 1, 'is_pinned' => false]
        ];

        foreach ($produtividadeWidgets as $widget) {
            PresetWidget::create(array_merge($widget, ['preset_id' => $produtividade->id]));
        }

        // Preset 2: Financeiro
        $financeiro = DashboardPreset::create([
            'name' => 'Financeiro',
            'slug' => 'financeiro',
            'description' => 'Visão completa das finanças e orçamentos',
            'layout_config' => [
                'columns' => 4,
                'gap' => 16,
                'padding' => 24
            ],
            'icon' => 'dollar-sign',
            'is_system' => true,
            'is_active' => true
        ]);

        // Widgets do preset Financeiro
        $financeiroWidgets = [
            ['widget_type' => 'financial_summary', 'title' => 'Resumo Financeiro', 'size' => 'L', 'position_x' => 0, 'position_y' => 0, 'width' => 3, 'height' => 2, 'is_pinned' => true],
            ['widget_type' => 'budgets', 'title' => 'Orçamentos', 'size' => 'M', 'position_x' => 3, 'position_y' => 0, 'width' => 1, 'height' => 2, 'is_pinned' => true],
            ['widget_type' => 'top_clients', 'title' => 'Top Clientes', 'size' => 'M', 'position_x' => 0, 'position_y' => 2, 'width' => 2, 'height' => 1, 'is_pinned' => false],
            ['widget_type' => 'portfolio_metrics', 'title' => 'Métricas de Portfólio', 'size' => 'M', 'position_x' => 2, 'position_y' => 2, 'width' => 2, 'height' => 1, 'is_pinned' => false]
        ];

        foreach ($financeiroWidgets as $widget) {
            PresetWidget::create(array_merge($widget, ['preset_id' => $financeiro->id]));
        }

        // Preset 3: Projetos
        $projetos = DashboardPreset::create([
            'name' => 'Projetos',
            'slug' => 'projetos',
            'description' => 'Gestão completa de projetos e portfólio',
            'layout_config' => [
                'columns' => 4,
                'gap' => 16,
                'padding' => 24
            ],
            'icon' => 'briefcase',
            'is_system' => true,
            'is_active' => true
        ]);

        // Widgets do preset Projetos
        $projetosWidgets = [
            ['widget_type' => 'pipeline', 'title' => 'Pipeline de Projetos', 'size' => 'L', 'position_x' => 0, 'position_y' => 0, 'width' => 2, 'height' => 2, 'is_pinned' => true],
            ['widget_type' => 'portfolio_metrics', 'title' => 'Métricas de Portfólio', 'size' => 'L', 'position_x' => 2, 'position_y' => 0, 'width' => 2, 'height' => 2, 'is_pinned' => true],
            ['widget_type' => 'upcoming_deadlines', 'title' => 'Prazos Próximos', 'size' => 'M', 'position_x' => 0, 'position_y' => 2, 'width' => 2, 'height' => 1, 'is_pinned' => false],
            ['widget_type' => 'top_clients', 'title' => 'Top Clientes', 'size' => 'M', 'position_x' => 2, 'position_y' => 2, 'width' => 2, 'height' => 1, 'is_pinned' => false]
        ];

        foreach ($projetosWidgets as $widget) {
            PresetWidget::create(array_merge($widget, ['preset_id' => $projetos->id]));
        }
    }
}
