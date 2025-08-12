# Guia de Implementação - Página 360° do Cliente

## Priorização 80/20 - Implementação Incremental

### Fase 1: Fundação (Sem Quebrar Funcionalidade Atual)

**Objetivo**: Manter toda funcionalidade existente e adicionar estrutura base

#### 1.1 Reestruturação do Componente Livewire

```php
// Evoluir ClienteDetalhes.php para Cliente360.php
// Manter todos os métodos existentes
// Adicionar propriedade $activeTab = 'pagamentos' (aba atual como padrão)
```

#### 1.2 Implementar Sistema de Abas

```blade
<!-- Usar Flowbite Tabs -->
<div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
        <li class="mr-2">
            <button wire:click="setActiveTab('visao-geral')" 
                    class="{{ $activeTab === 'visao-geral' ? 'border-blue-600 text-blue-600' : 'border-transparent' }}">
                Visão Geral
            </button>
        </li>
        <li class="mr-2">
            <button wire:click="setActiveTab('orcamentos')">
                Orçamentos
            </button>
        </li>
        <li class="mr-2">
            <button wire:click="setActiveTab('pagamentos')">
                Pagamentos / Extrato
            </button>
        </li>
    </ul>
</div>
```

#### 1.3 Mover Conteúdo Atual para Aba "Pagamentos"

* Transferir todo HTML existente para dentro da aba

* Manter filtros de data e botão PDF exatamente iguais

* **Zero quebra de funcionalidade**

### Fase 2: Header e KPIs Aprimorados

#### 2.1 Header do Cliente

```blade
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cliente->name }}</h1>
            <div class="flex gap-2 mt-2">
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Ativo</span>
                @if($this->isRecorrente())
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Recorrente</span>
                @endif
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('orcamentos.create', ['cliente_id' => $cliente->id]) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Novo Orçamento
            </a>
            <a href="{{ route('clientes.extrato.pdf', ['cliente' => $cliente->id, 'data_inicio' => $dataInicio, 'data_fim' => $dataFim]) }}" 
               target="_blank" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Gerar Extrato (PDF)
            </a>
        </div>
    </div>
</div>
```

#### 2.2 KPIs Expandidos

```php
// Adicionar ao componente Livewire
public function calcularKPIsExpandidos()
{
    $totalOrcamentos = $this->cliente->orcamentos()->count();
    $orcamentosAprovados = $this->cliente->orcamentos()->where('status', 'Aprovado')->count();
    
    $this->kpis = [
        'orcamentos_enviados' => $totalOrcamentos,
        'taxa_aprovacao' => $totalOrcamentos > 0 ? round(($orcamentosAprovados / $totalOrcamentos) * 100, 1) : 0,
        'receita_total' => $this->totalPago, // já existe
        'a_receber' => $this->saldoDevedor   // já existe
    ];
}
```

### Fase 3: Aba de Orçamentos

#### 3.1 Tabela de Orçamentos com Status

```blade
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-medium mb-4">Orçamentos do Cliente</h3>
    
    <!-- Filtros -->
    <div class="flex gap-4 mb-4">
        <select wire:model.live="statusFilter" class="rounded-md border-gray-300">
            <option value="todos">Todos os Status</option>
            <option value="Analisando">Analisando</option>
            <option value="Aprovado">Aprovado</option>
            <option value="Rejeitado">Rejeitado</option>
        </select>
    </div>
    
    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">#ID / Título</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-right">Valor Total</th>
                    <th class="px-4 py-2 text-right">Pago</th>
                    <th class="px-4 py-2 text-right">Pendente</th>
                    <th class="px-4 py-2 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getOrcamentosFiltrados() as $orcamento)
                    <tr class="border-b">
                        <td class="px-4 py-2">
                            <div>
                                <span class="font-medium">#{{ $orcamento->numero }}</span>
                                <div class="text-gray-500">{{ $orcamento->titulo }}</div>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            @include('components.status-badge', ['status' => $orcamento->status])
                        </td>
                        <td class="px-4 py-2 text-right">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right text-green-600">R$ {{ number_format($orcamento->total_pago ?? 0, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right text-red-600">R$ {{ number_format($orcamento->valor_total - ($orcamento->total_pago ?? 0), 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex gap-1 justify-center">
                                <a href="{{ route('orcamentos.show', $orcamento) }}" class="text-blue-600 hover:text-blue-800">Ver</a>
                                <a href="{{ route('orcamentos.pdf', $orcamento) }}" class="text-gray-600 hover:text-gray-800">PDF</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

#### 3.2 Component de Status Badge

```blade
<!-- resources/views/components/status-badge.blade.php -->
@php
$classes = [
    'Analisando' => 'bg-yellow-100 text-yellow-800',
    'Aprovado' => 'bg-green-100 text-green-800', 
    'Rejeitado' => 'bg-red-100 text-red-800',
    'Finalizado' => 'bg-blue-100 text-blue-800',
    'Vencido' => 'bg-gray-100 text-gray-800'
];
@endphp

<span class="{{ $classes[$status] ?? 'bg-gray-100 text-gray-800' }} text-xs font-medium px-2.5 py-0.5 rounded">
    {{ $status }}
</span>
```

### Fase 4: Melhorias na Aba Pagamentos

#### 4.1 Agrupamento por Orçamento

```blade
<div class="space-y-4">
    @foreach($this->getOrcamentosComPagamentos() as $orcamento)
        <div class="border rounded-lg">
            <!-- Cabeçalho do Accordion -->
            <div class="bg-gray-50 p-4 cursor-pointer" 
                 x-data="{ open: false }" 
                 @click="open = !open">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-medium">Orçamento #{{ $orcamento->numero }} - {{ $orcamento->titulo }}</h4>
                        <p class="text-sm text-gray-600">{{ $orcamento->data_emissao->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</div>
                        <div class="text-sm">
                            <span class="text-green-600">Pago: R$ {{ number_format($orcamento->total_pago, 2, ',', '.') }}</span> |
                            <span class="text-red-600">Pendente: R$ {{ number_format($orcamento->valor_total - $orcamento->total_pago, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Conteúdo do Accordion -->
            <div x-show="open" x-collapse class="p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Data</th>
                            <th class="text-left py-2">Método</th>
                            <th class="text-right py-2">Valor</th>
                            <th class="text-left py-2">Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamento->pagamentos as $pagamento)
                            <tr class="border-b">
                                <td class="py-2">{{ $pagamento->data_pagamento->format('d/m/Y') }}</td>
                                <td class="py-2">{{ $pagamento->metodo_pagamento ?? 'N/A' }}</td>
                                <td class="py-2 text-right text-green-600">R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</td>
                                <td class="py-2">{{ $pagamento->observacoes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
```

### Fase 5: Timeline Simples (Visão Geral)

#### 5.1 Timeline Básica

```php
// Método no componente Livewire
public function getTimelineEvents()
{
    $events = collect();
    
    // Eventos de orçamentos
    foreach($this->cliente->orcamentos()->latest()->take(20)->get() as $orcamento) {
        $events->push([
            'type' => 'orcamento_criado',
            'title' => "Orçamento #{$orcamento->numero} criado",
            'description' => $orcamento->titulo,
            'date' => $orcamento->created_at,
            'icon' => 'document-text',
            'color' => 'blue',
            'link' => route('orcamentos.show', $orcamento)
        ]);
        
        if($orcamento->status === 'Aprovado') {
            $events->push([
                'type' => 'orcamento_aprovado',
                'title' => "Orçamento #{$orcamento->numero} aprovado",
                'description' => "Valor: R$ " . number_format($orcamento->valor_total, 2, ',', '.'),
                'date' => $orcamento->updated_at,
                'icon' => 'check-circle',
                'color' => 'green',
                'link' => route('orcamentos.show', $orcamento)
            ]);
        }
    }
    
    // Eventos de pagamentos
    foreach($this->pagamentos as $pagamento) {
        $events->push([
            'type' => 'pagamento_recebido',
            'title' => "Pagamento recebido",
            'description' => "R$ " . number_format($pagamento->valor_pago, 2, ',', '.') . " - Orçamento #{$pagamento->orcamento->numero}",
            'date' => $pagamento->data_pagamento,
            'icon' => 'currency-dollar',
            'color' => 'green',
            'link' => route('orcamentos.show', $pagamento->orcamento)
        ]);
    }
    
    return $events->sortByDesc('date')->take(50);
}
```

## Cronograma de Implementação

### Semana 1: Fundação

* [ ] Criar componente Cliente360.php baseado no ClienteDetalhes.php existente

* [ ] Implementar sistema de abas com Flowbite

* [ ] Migrar conteúdo atual para aba "Pagamentos"

* [ ] Testar que nada quebrou

### Semana 2: Header e KPIs

* [ ] Criar header com nome do cliente e tags

* [ ] Adicionar botões de ação rápida

* [ ] Expandir KPIs com taxa de aprovação e orçamentos enviados

* [ ] Criar component de status badge

### Semana 3: Aba Orçamentos

* [ ] Implementar tabela de orçamentos

* [ ] Adicionar filtros por status

* [ ] Calcular valores pago/pendente por orçamento

* [ ] Adicionar ações (ver, PDF, etc.)

### Semana 4: Melhorias e Timeline

* [ ] Implementar agrupamento por orçamento na aba Pagamentos

* [ ] Criar timeline básica na aba Visão Geral

* [ ] Adicionar filtros na timeline

* [ ] Testes finais e ajustes de UX

## Benefícios Imediatos

1. **Organização**: Informações estruturadas em abas lógicas
2. **Contexto**: Visão completa do relacionamento com o cliente
3. **Eficiência**: Ações rápidas no header
4. **Insights**: KPIs de performance comercial
5. **Histórico**: Timeline unificada de eventos
6. **Compatibilidade**: Mantém toda funcionalidade existente

