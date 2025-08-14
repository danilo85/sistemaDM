<div class="p-6 bg-white rounded-lg shadow-md">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Executar Seeder de Categorias</h3>
    
    @if(!$executado)
        <p class="text-gray-600 mb-4">
            Este seeder irá criar 15 categorias financeiras (7 receitas e 8 despesas) no sistema.
        </p>
        
        <button 
            wire:click="executarSeeder" 
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors"
        >
            Executar Seeder
        </button>
    @endif
    
    @if($mensagem)
        <div class="mt-4 p-4 rounded-lg {{ $executado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $mensagem }}
        </div>
    @endif
    
    @if($executado)
        <div class="mt-4">
            <a href="{{ route('categorias.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors inline-block">
                Ver Categorias
            </a>
        </div>
    @endif
</div>