<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Perfil e Configurações') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Coluna Principal (Esquerda) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Card de Informações do Perfil --}}
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                    {{-- Card de Atualização de Senha --}}
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Coluna Lateral (Direita) --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Card de Exclusão de Conta --}}
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
