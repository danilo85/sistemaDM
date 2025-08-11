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