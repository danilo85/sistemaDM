<div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Usuário</th>
                    <th scope="col" class="px-6 py-3">Função</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="h-10 object-contain" src="{{ $user->logo_path ? asset('storage/' . $user->logo_path) : 'https://placehold.co/40x40/EBF4FF/7F9CF5?text=' . substr($user->name, 0, 1) }}" alt="{{ $user->name }} logo">
                            <div class="pl-3">
                                <div class="text-base font-semibold">{{ $user->name }}</div>
                                <div class="font-normal text-gray-500">{{ $user->email }}</div>
                            </div>  
                        </th>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ $user->getRoleNames()->first() ?? 'Usuário' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center" title="Visto por último: {{ optional($user->last_seen_at)->diffForHumans() ?? 'Nunca' }}">
                                @if($user->isOnline())
                                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div> Online
                                @else
                                    <div class="h-2.5 w-2.5 rounded-full bg-gray-500 mr-2"></div> Offline
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openPermissionsModal({{ $user->id }})" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700" title="Gerir Permissões">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                                </button>
                                @if($user->id !== Auth::id())
                                    {{-- CORREÇÃO: Botão agora abre o modal de exclusão --}}
                                    <button wire:click="openDeleteModal({{ $user->id }})" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700" title="Excluir">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.134H8.09c-1.18 0-2.09.954-2.09 2.134v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center">Nenhum usuário encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6">
        {{ $users->links() }}
    </div>

    {{-- MODAL DE GESTÃO DE PERMISSÕES --}}
    @if($managingPermissionsForUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl" @click.away="$wire.closePermissionsModal()">
                <div class="p-6 border-b dark:border-gray-700"><h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Gerir Permissões para: {{ $managingPermissionsForUser->name }}</h3><p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Selecione os módulos aos quais este usuário terá acesso.</p></div>
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    <div class="space-y-4">
                        @foreach($allPermissions as $module => $permissions)
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300 capitalize">{{ $module }}</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"><input type="checkbox" wire:model.live="userPermissions" value="{{ $permission->name }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"><span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ Str::replaceFirst('acessar_', '', $permission->name) }}</span></label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 border-t dark:border-gray-700 space-y-4">
                    <div><label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirme a sua senha para salvar</label><input type="password" wire:model="passwordConfirmation" id="password_confirmation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500">@error('passwordConfirmation') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror</div>
                    <div class="flex justify-end gap-4"><button wire:click="closePermissionsModal" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancelar</button><button wire:click="updateUserPermissions" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Salvar Permissões</button></div>
                </div>
            </div>
        </div>
    @endif

    {{-- NOVO MODAL DE EXCLUSÃO DE USUÁRIO --}}
    @if($deletingUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md" @click.away="$wire.closeDeleteModal()">
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Excluir Usuário: {{ $deletingUser->name }}?</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Esta ação é irreversível e irá apagar todos os dados associados a este usuário.</p>
                </div>
                <div class="p-6 border-t dark:border-gray-700 space-y-4">
                    <div>
                        <label for="delete_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirme sua senha para continuar</label>
                        <input type="password" wire:model="deletePasswordConfirmation" id="delete_password_confirmation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:border-red-500 focus:ring-red-500">
                        @error('deletePasswordConfirmation') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-center gap-4">
                        <button wire:click="closeDeleteModal" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancelar</button>
                        <button wire:click="confirmDeleteUser" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5">Sim, Excluir</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
