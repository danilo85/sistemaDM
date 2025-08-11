@props([
    'initialValue' => ''
])

<div
    wire:ignore
    x-data="{
        value: @entangle($attributes->wire('model')),
        init() {
            const trixEditor = this.$refs.trix;

            // Define o valor inicial que vem do Livewire
            trixEditor.editor.loadHTML(this.value);

            // Listener: Quando o Trix muda, atualiza o Livewire
            trixEditor.addEventListener('trix-change', () => {
                this.value = trixEditor.value;
            });

            // Listener: Quando o Livewire muda (ex: reset), atualiza o Trix
            $watch('value', (newValue) => {
                if (newValue === '' || newValue === null) {
                    trixEditor.editor.loadHTML('');
                }
            });
        }
    }"
>
    <input type="hidden" x-ref="input" :value="value">
    <trix-editor x-ref="trix" {{ $attributes->merge(['class' => 'trix-content ... rounded-md shadow-sm']) }}></trix-editor>
</div>