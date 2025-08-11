<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Extrato - {{ $cliente->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; }
        .summary { margin-bottom: 20px; border: 1px solid #eee; padding: 15px; }
        .summary-item { display: inline-block; width: 32%; text-align: center; }
        .summary-item p { margin: 0; font-size: 11px; text-transform: uppercase; color: #666; }
        .summary-item h2 { margin: 5px 0; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Extrato de Conta Corrente</h1>
        <p><strong>Cliente:</strong> {{ $cliente->name }}</p>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <p>Total em Projetos</p>
            <h2>R$ {{ number_format($totalEmProjetos, 2, ',', '.') }}</h2>
        </div>
        <div class="summary-item">
            <p>Total Pago</p>
            <h2 style="color: green;">R$ {{ number_format($totalPago, 2, ',', '.') }}</h2>
        </div>
        <div class="summary-item">
            <p>Saldo Devedor</p>
            <h2 style="color: red;">R$ {{ number_format($saldoDevedor, 2, ',', '.') }}</h2>
        </div>
    </div>

    <h3>Débitos (Projetos Aprovados)</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th class="text-right">Valor Total</th>
                <th class="text-right">Falta Pagar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orcamentos as $orcamento)
                <tr>
                    <td>{{ optional($orcamento->updated_at)->format('d/m/Y') }}</td>
                    <td>{{ $orcamento->titulo }}</td>
                    <td class="text-right">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</td>
                    @php
                        $faltaPagar = $orcamento->valor_total - $orcamento->pagamentos_sum_valor_pago;
                    @endphp
                    <td class="text-right" style="font-weight: bold; color: {{ $faltaPagar > 0 ? 'red' : 'green' }};">
                        R$ {{ number_format($faltaPagar, 2, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align: center;">Nenhum projeto no período.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>Créditos (Pagamentos Recebidos)</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th class="text-right">Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagamentos as $pagamento)
                <tr>
                    <td>{{ optional($pagamento->data_pagamento)->format('d/m/Y') }}</td>
                    <td>Pagamento ref. Orçamento #{{ $pagamento->orcamento->numero }}</td>
                    <td class="text-right" style="color: green;">R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align: center;">Nenhum pagamento no período.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
