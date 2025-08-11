@props([
    // 'value' será o texto inicial que vem do Livewire
    'value' => '',
])

<div
    wire:ignore 
    x-data="{
        // A variável 'value' do Alpine será conectada à propriedade do Livewire
        value: @entangle($attributes->wire('model')),

        // A função init() roda quando o componente é carregado
        init() {
            const trixEditor = this.$refs.trix;

            // Define o valor inicial no editor Trix
            trixEditor.editor.loadHTML(this.value);

            // Listener: QUANDO o conteúdo do Trix muda...
            trixEditor.addEventListener('trix-change', () => {
                // ...nós atualizamos a nossa variável 'value' do Alpine.
                // Como ela está conectada com @entangle, o Livewire será notificado.
                this.value = trixEditor.value;
            });

            // Listener: QUANDO o Livewire muda o valor por trás dos panos...
            $watch('value', (newValue) => {
                // ...nós atualizamos o editor Trix na tela.
                // Isso é importante para funções como 'resetar formulário'.
                if (newValue !== trixEditor.value) {
                    trixEditor.editor.loadHTML(newValue);
                }
            });
        }
    }"
>
    {{-- O input escondido não é mais necessário, o @entangle cuida de tudo --}}

    {{-- O editor Trix que o usuário vê --}}
    <trix-editor
        x-ref="trix"
        {{ $attributes->whereDoesntStartWith('wire:model') }}
        class="trix-content block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
    ></trix-editor>
</div>