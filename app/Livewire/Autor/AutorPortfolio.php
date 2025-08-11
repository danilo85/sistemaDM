<?php

namespace App\Livewire\Autor;

use App\Models\Autor;
use App\Models\Orcamento;
use Livewire\Component;

class AutorPortfolio extends Component
{
    public Autor $autor;

    // Propriedades para os KPIs
    public int $totalProjetos = 0;
    public float $valorTotalProjetos = 0; // NOVO: Valor total dos projetos
    public ?Autor $principalParceiro = null;

    // Coleção de orçamentos para a linha do tempo
    public $orcamentos;

    public function mount()
    {
        $this->carregarDados();
    }

    public function carregarDados()
    {
        // A busca agora também carrega a soma dos pagamentos de cada orçamento
        $this->orcamentos = $this->autor->orcamentos()
            ->with(['cliente', 'autores'])
            ->withSum('pagamentos', 'valor_pago') // NOVO: Carrega a soma dos pagamentos
            ->where('status', '!=', 'Rejeitado')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->totalProjetos = $this->orcamentos->count();
        
        // NOVO: Calcula a soma do valor total de todos os projetos
        $this->valorTotalProjetos = $this->orcamentos->sum('valor_total');

        $this->principalParceiro = $this->encontrarPrincipalParceiro();
    }

    private function encontrarPrincipalParceiro(): ?Autor
    {
        $coautoresIds = $this->orcamentos->flatMap(function ($orcamento) {
            return $orcamento->autores->where('id', '!=', $this->autor->id)->pluck('id');
        });

        if ($coautoresIds->isEmpty()) {
            return null;
        }

        $parceiroIdMaisFrequente = $coautoresIds->countBy()->sortDesc()->keys()->first();
        return Autor::find($parceiroIdMaisFrequente);
    }

    public function render()
    {
        return view('livewire.autor.autor-portfolio');
    }
}
