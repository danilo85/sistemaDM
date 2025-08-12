@props(['name', 'value' => ''])

<div
    wire:ignore
    x-data="{
        value: '{{ $value }}',
        instance: null,
        init() {
            this.instance = flatpickr(this.$refs.input, {
                dateFormat: 'd/m/Y',
                defaultDate: this.value,
                onChange: (selectedDates, dateStr) => {
                    this.value = dateStr;
                    // Sincronizar com Livewire
                    if (this.$wire) {
                        this.$wire.set('{{ $name }}', dateStr);
                    }
                }
            });
            
            // Observar mudanças do Livewire para atualizar o flatpickr
            this.$watch('$wire.{{ $name }}', (newValue) => {
                if (newValue !== this.value && this.instance) {
                    this.value = newValue;
                    this.instance.setDate(newValue, false);
                }
            });
        }
    }"
>
    <input 
        {{ $attributes->merge(['class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500']) }}
        type="text"
        name="{{ $name }}"
        x-ref="input"
        x-model="value"
    >
</div>