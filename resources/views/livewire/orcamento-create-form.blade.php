<div>
    <form wire:submit="save" class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Cliente --}}
            <div class="md:col-span-2" wire:ignore>
                <label for="select-cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                <select id="select-cliente" placeholder="Digite para buscar ou criar..."></select>
                @error('cliente_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Autores --}}
            <div class="md:col-span-2" wire:ignore>
                <label for="select-autores" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Autores</label>
                <select id="select-autores" multiple placeholder="Digite para buscar ou criar..."></select>
                @error('autores') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Título --}}
            <div class="md:col-span-2">
                <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Título</label>
                <input type="text" wire:model="titulo" id="titulo" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                @error('titulo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            {{-- Descrição --}}
            <div class="md:col-span-2" 
                 x-data="{
                     previousState: '',
                     wrapText(tag) {
                         const textarea = this.$refs.textarea;
                         this.previousState = textarea.value;
                         const start = textarea.selectionStart;
                         const end = textarea.selectionEnd;
                         if (start === end) return;
                         const selectedText = textarea.value.substring(start, end);
                         const newText = textarea.value.substring(0, start) + `<${tag}>` + selectedText + `</${tag}>` + textarea.value.substring(end);
                         $wire.set('descricao', newText);
                     },
                     addList(listType) {
                         const textarea = this.$refs.textarea;
                         this.previousState = textarea.value;
                         const start = textarea.selectionStart;
                         const end = textarea.selectionEnd;
                         const selectedText = textarea.value.substring(start, end);
                         if (selectedText.length === 0) return;
                         const lines = selectedText.split('\n');
                         let list = `<${listType}>\n`;
                         lines.forEach(line => {
                             if (line.trim() !== '') {
                                 list += `    <li>${line.trim()}</li>\n`;
                             }
                         });
                         list += `</${listType}>`;
                         const newText = textarea.value.substring(0, start) + list + textarea.value.substring(end);
                         $wire.set('descricao', newText);
                     },
                     addLink() {
                         const textarea = this.$refs.textarea;
                         const url = prompt('Digite a URL do link:', 'https://');
                         if (url) {
                             this.previousState = textarea.value;
                             const start = textarea.selectionStart;
                             const end = textarea.selectionEnd;
                             const selectedText = textarea.value.substring(start, end) || 'texto do link';
                             const newText = textarea.value.substring(0, start) + `<a href='${url}' target='_blank'>` + selectedText + `</a>` + textarea.value.substring(end);
                             $wire.set('descricao', newText);
                         }
                     },
                     undo() {
                         if (this.previousState) {
                             $wire.set('descricao', this.previousState);
                         }
                     },
                     clear() {
                         this.previousState = this.$refs.textarea.value;
                         $wire.set('descricao', '');
                     }
                 }"
            >
                <label for="descricao" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição Detalhada</label>

                <div class="mt-1 flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-900 rounded-t-md border-x border-t border-gray-200 dark:border-gray-700 flex-wrap">
                    <button type="button" @click="wrapText('b')" class="font-bold p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Negrito">B</button>
                    <button type="button" @click="wrapText('i')" class="italic p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Itálico">I</button>
                    <button type="button" @click="wrapText('u')" class="underline p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Sublinhado">U</button>
                    <button type="button" @click="wrapText('s')" class="line-through p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Riscado">S</button>
                    <div class="h-5 border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                    <button type="button" @click="addList('ul')" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Lista de Itens">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" /></svg>
                    </button>
                    <button type="button" @click="addList('ol')" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Lista Numerada">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.52 5.683a.75.75 0 01.75-.75h.75a.75.75 0 01.75.75v.5a.75.75 0 01-1.5 0v-.5zM4 9.25a.75.75 0 00-1.5 0v.5a.75.75 0 001.5 0v-.5zM2.52 14.183a.75.75 0 01.75-.75h.75a.75.75 0 01.75.75v.5a.75.75 0 01-1.5 0v-.5z"></path><path d="M6 5.5a.5.5 0 01.5-.5h9a.5.5 0 010 1h-9a.5.5 0 01-.5-.5zM6 10a.5.5 0 01.5-.5h9a.5.5 0 010 1h-9a.5.5 0 01-.5-.5zM6.5 14a.5.5 0 000 1h9a.5.5 0 000-1h-9z"></path></svg>
                    </button>
                    <button type="button" @click="addLink()" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Adicionar Link">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.665l3-3.001z"></path><path d="M4.468 12.232a2.5 2.5 0 010-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 005.656 5.656l3-3a4 4 0 00-.225-5.865.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.665l-3 3a2.5 2.5 0 01-3.536 0z"></path></svg>
                    </button>
                    <div class="h-5 border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                    <button type="button" @click="undo()" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Desfazer Formatação">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                    </button>
                    <button type="button" @click="clear()" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700" title="Limpar Tudo">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75L14.25 12m0 0l2.25 2.25M14.25 12L12 14.25m-2.58 4.92l-6.375-6.375a1.125 1.125 0 010-1.59L9.42 4.83c.211-.211.498-.33.796-.33H19.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-9.284c-.298 0-.585-.119-.796-.33z" /></svg>
                    </button>
                </div>

                <textarea 
                    wire:model.live.debounce.750ms="descricao" 
                    x-ref="textarea"
                    id="descricao" 
                    rows="10" 
                    class="block w-full p-2.5 rounded-b-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 -mt-px">
                </textarea>
                @error('descricao') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                {{-- SUGESTÃO INTELIGENTE --}}
                @if($sugestao)
                <div class="mt-2 p-3 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    {!! $sugestao !!}
                </div>
                @endif
            </div>

            {{-- Valor Total --}}
            <div>
                <label for="valor_total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor Total (R$)</label>
                <x-money-input name="valor_total" wire:model="valor_total" value="{{ $valor_total }}" id="valor_total" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" />
                @error('valor_total') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Prazo de Entrega --}}
            <div>
                <label for="prazo_entrega_dias" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prazo (dias)</label>
                <input type="number" wire:model="prazo_entrega_dias" id="prazo_entrega_dias" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                @error('prazo_entrega_dias') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Data de Emissão --}}
            <div>
                <label for="data_emissao" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data de Emissão</label>
                <x-date-picker name="data_emissao" wire:model="data_emissao" value="{{ $data_emissao }}" id="data_emissao" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" />
                @error('data_emissao') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Data de Validade --}}
            <div>
                <label for="data_validade" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data de Validade</label>
                <x-date-picker name="data_validade" wire:model="data_validade" value="{{ $data_validade }}" id="data_validade" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" />
                @error('data_validade') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            {{-- Condições de Pagamento --}}
            <div class="md:col-span-2">
                <label for="condicoes_pagamento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Condições de Pagamento</label>
                <textarea wire:model="condicoes_pagamento" id="condicoes_pagamento" rows="3" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500"></textarea>
                @error('condicoes_pagamento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            </
            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('orcamentos.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">Cancelar</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Salvar Orçamento</button>
            </div>
        
    </form>

    @push('styles')
    {{-- ESTILO PARA O TOM-SELECT E SUAS TAGS --}}
    <style>
        .ts-control {
            background-color: #f3f4f6 !important; /* bg-gray-100 */
            border: none !important;
            border-radius: 0.5rem !important; /* rounded-lg */
            padding: 0.625rem !important; /* p-2.5 */
        }
        .dark .ts-control {
            background-color: #1f2937 !important; /* dark:bg-gray-900 */
        }
        .ts-control.focus {
            box-shadow: 0 0 0 2px #3b82f6 !important; /* focus:ring-2 focus:ring-blue-500 */
        }
        .ts-dropdown, .ts-dropdown .create {
            background-color: #ffffff !important;
        }
        .dark .ts-dropdown, .dark .ts-dropdown .create {
            background-color: #374151 !important; /* dark:bg-gray-600 */
            border-color: #4b5563 !important; /* dark:border-gray-500 */
        }
        .dark .ts-dropdown .option {
            color: #d1d5db; /* dark:text-gray-300 */
        }
        
        /* Estilo para TAGS EXISTENTES (Verde) */
        .ts-control .item.item-existing {
            background-color: #d1fae5 !important; /* green-100 */
            color: #065f46 !important; /* green-800 */
        }
        .dark .ts-control .item.item-existing {
            background-color: #064e3b !important; /* dark:green-800 */
            color: #a7f3d0 !important; /* dark:green-300 */
        }

        /* Estilo para TAGS NOVAS (Vermelho) */
        .ts-control .item.item-new {
            background-color: #fee2e2 !important; /* red-100 */
            color: #991b1b !important; /* red-800 */
        }
        .dark .ts-control .item.item-new {
            background-color: #7f1d1d !important; /* dark:red-800 */
            color: #fecaca !important; /* dark:red-300 */
        }
        .dark .ts-control .item.item-new .remove {
             border-left-color: #ef4444 !important; /* dark:red-500 */
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            let initialClienteId = @json($this->cliente_id);
            let initialAutores = @json($this->autores);
            let clienteOptions = @json($clientes->map(fn($c) => ['id' => $c->id, 'name' => $c->name]));
            let autorOptions = @json($autoresDisponiveis->map(fn($a) => ['id' => $a->id, 'name' => $a->name]));

            const tomSelectOptions = (options) => ({
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                create: true,
                options: options,
                render: {
                    item: function(item, escape) {
                        const isNew = isNaN(parseInt(item.id));
                        const className = isNew ? 'item-new' : 'item-existing';
                        return `<div class="item ${className}">${escape(item.name)}</div>`;
                    },
                    option_create: function(data, escape) {
                        return `<div class="create">Adicionar <strong>${escape(data.input)}</strong>&hellip;</div>`;
                    }
                }
            });

            const clienteSelect = new TomSelect('#select-cliente',{
                ...tomSelectOptions(clienteOptions),
                maxItems: 1,
                items: initialClienteId ? [initialClienteId] : [],
                onChange: (value) => @this.set('cliente_id', value),
            });

            const autoresSelect = new TomSelect('#select-autores',{
                ...tomSelectOptions(autorOptions),
                plugins: ['remove_button'],
                items: initialAutores,
                onChange: (value) => @this.set('autores', value),
            });
        });
    </script>
    @endpush
</div>
