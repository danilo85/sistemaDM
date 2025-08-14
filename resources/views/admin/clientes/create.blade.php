<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Adicionar Novo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('clientes.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            {{-- Logo do Cliente --}}
                            <div x-data="{ logoPreview: '', dragging: false, handleFileSelect(event) { const file = event.target.files[0]; if (file) { this.logoPreview = URL.createObjectURL(file); } }, handleDrop(event) { const file = event.dataTransfer.files[0]; if (file) { document.getElementById('logo').files = event.dataTransfer.files; this.logoPreview = URL.createObjectURL(file); } } }">
                                <label for="logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Logo/Foto do Cliente</label>
                                <input id="logo" name="logo" type="file" class="hidden" @change="handleFileSelect($event)" accept="image/png, image/jpeg, image/jpg">
                                <div @click="document.getElementById('logo').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleDrop($event)" class="mt-1 cursor-pointer flex flex-col justify-center items-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-8 transition-colors h-32" :class="{'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': dragging}">
                                    <template x-if="logoPreview"><img :src="logoPreview" alt="Prévia da Logo" class="h-20 w-20 rounded-full object-cover"></template>
                                    <template x-if="!logoPreview">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                            <p class="mt-2 text-xs text-center text-gray-600 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Clique</span> ou arraste</p>
                                        </div>
                                    </template>
                                </div>
                                @error('logo')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nome --}}
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                                <input id="name" name="name" type="text" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" required autofocus value="{{ old('name') }}">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Pessoa de Contato --}}
                            <div>
                                <label for="contact_person" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pessoa de Contato</label>
                                <input id="contact_person" name="contact_person" type="text" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('contact_person') }}">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                <input id="email" name="email" type="email" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('email') }}">
                                {{-- A CORREÇÃO ESTÁ AQUI --}}
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Telefone --}}
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone</label>
                                <x-phone-input name="phone" wire:model="phone" id="phone" />
                            </div>
                        </div>

                        {{-- Botões de Ação --}}
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
                            <a href="{{ route('clientes.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                Voltar
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Salvar Cliente
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
