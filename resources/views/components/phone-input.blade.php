@props(['disabled' => false])

{{-- 
    Componente dedicado para a máscara de telefone, usando Alpine.js e IMask.js.
--}}
<div
    wire:ignore
    x-data
    x-init="
        let mask = IMask($refs.input, {
            mask: '(00) 00000-0000'
        });

        // Usa Livewire.entangle para obter uma propriedade reativa
        let value = Livewire.entangle($wire, '{{ $attributes->get('wire:model') }}');

        // Define o valor inicial na máscara a partir do Livewire
        mask.value = value.get() || '';

        // Quando o usuário digita, atualiza a propriedade no Livewire
        mask.on('accept', () => {
            value.set(mask.value);
        });

        // Quando a propriedade no Livewire muda, atualiza a máscara
        $watch(() => value.get(), (newValue) => {
            if (mask.value != newValue) {
                mask.value = newValue || '';
            }
        });
    "
>
    <input 
        x-ref="input"
        type="text"
        placeholder="(99) 99999-9999"
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->except('wire:model')->merge(['class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500']) !!}
    >
</div>
