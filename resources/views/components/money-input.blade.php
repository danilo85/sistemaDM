@props(['disabled' => false])

{{-- 
    Componente de máscara de moeda (BRL) usando Alpine.js e IMask.js.
    Sincroniza de forma reativa com o Livewire sem a necessidade de um hook global.
--}}
<div
    wire:ignore
    x-data="{
        value: @entangle($attributes->wire('model')), // Sincroniza a propriedade 'value' com o wire:model
        imask: null,
        init() {
            // 1. Inicializa a máscara no input visível
            this.imask = IMask(this.$refs.maskedInput, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        padFractionalZeros: true,
                        radix: ',',
                        mapToRadix: ['.'],
                        lazy: false,
                    }
                }
            });

            // 2. Define o valor inicial da máscara com base no valor do Livewire
            this.$nextTick(() => {
                this.imask.unmaskedValue = this.value || '';
            });

            // 3. Quando o usuário digita, atualiza a propriedade 'value' (que está ligada ao Livewire)
            this.imask.on('accept', () => {
                this.value = this.imask.unmaskedValue;
            });

            // 4. Observa por mudanças vindas do Livewire (ex: 'Quitar Orçamento' ou 'Editar')
            //    e atualiza o valor da máscara que o usuário vê.
            this.$watch('value', (newValue) => {
                if (this.imask.unmaskedValue !== newValue) {
                    this.imask.unmaskedValue = newValue || '';
                }
            });
        }
    }"
>
    {{-- Input visível para o usuário, controlado pelo IMask --}}
    <input 
        x-ref="maskedInput"
        type="text"
        {{ $disabled ? 'disabled' : '' }} 
        {{-- As classes do Flowbite/Tailwind são aplicadas aqui --}}
        class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500"
    >
</div>
