<div>
    {{-- CABEÇALHO COM TÍTULO E BUSCA --}}
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            {{-- TÍTULO E SUBTÍTULO À ESQUERDA --}}
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
                    Gestão de Usuários
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Gerencie usuários do sistema e suas permissões
                </p>
            </div>
            
            {{-- BUSCA DE USUÁRIOS À DIREITA --}}
            <div class="flex-shrink-0 w-full lg:w-80">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar usuários..."
                           class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                    {{-- Botão X para limpar busca --}}
                    @if($search)
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button wire:click="$set('search', '')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- GRID DE CARDS --}}
    @if($users->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($users as $user)
                <div wire:key="{{ $user->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                    {{-- CABEÇALHO DO CARD --}}
                    <div class="p-6 pb-4">
                        {{-- LOGO/AVATAR CENTRALIZADA --}}
                        <div class="flex justify-center mb-4">
                            <div class="flex-shrink-0">
                                @if($user->logo_path)
                                    <img src="{{ asset('storage/' . $user->logo_path) }}" alt="{{ $user->name }}" class="w-16 h-16 object-contain border-2 border-white dark:border-gray-600">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center border-2 border-white dark:border-gray-600">
                                        <span class="text-xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        {{-- NOME DO USUÁRIO CENTRALIZADO --}}
                        <div class="text-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                {{ $user->name }}
                            </h3>
                            <div class="flex justify-center mt-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $user->getRoleNames()->first() ?? 'Usuário' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $user->email }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:underline truncate">
                                    {{ $user->email }}
                                </a>
                            </div>
                        </div>
                        
                        {{-- ÚLTIMO ACESSO --}}
                        <div class="mb-4">
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">
                                    {{ optional($user->last_seen_at)->diffForHumans() ?? 'Nunca acessou' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- AÇÕES DO CARD --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            {{-- STATUS ONLINE/OFFLINE - APENAS BOLINHA --}}
                            <div class="flex items-center" title="Visto por último: {{ optional($user->last_seen_at)->diffForHumans() ?? 'Nunca' }}">
                                @if($user->isOnline())
                                    <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                @else
                                    <div class="h-3 w-3 rounded-full bg-gray-500"></div>
                                @endif
                            </div>
                            
                            {{-- BOTÕES DE AÇÃO --}}
                            <div class="flex items-center gap-4">
                                <button wire:click="openPermissionsModal({{ $user->id }})" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors" title="Gerir Permissões">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                    </svg>
                                </button>
                                
                                @if($user->id !== Auth::id())
                                    <button wire:click="openDeleteModal({{ $user->id }})" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Excluir">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum usuário encontrado</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if($search)
                    Nenhum usuário corresponde aos critérios de busca.
                @else
                    Nenhum usuário cadastrado no sistema.
                @endif
            </p>
        </div>
    @endif

    {{-- PAGINAÇÃO --}}
    @if($users->hasPages())
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            {{ $users->links() }}
        </div>
    @endif

    {{-- MODAL DE GESTÃO DE PERMISSÕES --}}
    @if($showPermissionsModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4" id="permissions-modal">
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                {{-- CABEÇALHO DO MODAL --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white">Gerenciar Permissões</h3>
                                <p class="text-blue-100 text-sm">{{ $managingPermissionsForUser->name ?? '' }}</p>
                            </div>
                        </div>
                        <button wire:click="closePermissionsModal" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- CONTEÚDO DO MODAL --}}
                <div class="flex-1 p-6 overflow-y-auto">
                    @if($managingPermissionsForUser)
                        {{-- INFORMAÇÕES DO USUÁRIO --}}
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700/50 dark:to-blue-900/20 p-6 rounded-xl border border-gray-200 dark:border-gray-600 mb-6">
                            <div class="flex flex-col items-center text-center">
                                <div class="mb-4">
                                    @if($managingPermissionsForUser->logo_path)
                                        <img src="{{ asset('storage/' . $managingPermissionsForUser->logo_path) }}" alt="{{ $managingPermissionsForUser->name }}" class="w-16 h-16 object-contain border-2 border-white dark:border-gray-600">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center border-2 border-white dark:border-gray-600">
                                            <span class="text-xl font-bold text-white">{{ substr($managingPermissionsForUser->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">{{ $managingPermissionsForUser->name }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $managingPermissionsForUser->email }}</p>
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            ID: {{ $managingPermissionsForUser->id }}
                                        </span>
                                        @if($managingPermissionsForUser->getRoleNames()->isNotEmpty())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                                {{ $managingPermissionsForUser->getRoleNames()->first() }}
                                            </span>
                                        @endif
                                        {{-- Status Online/Offline --}}
                                        @if($managingPermissionsForUser->isOnline())
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        @else
                                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- SEÇÃO DE FUNÇÃO PREDEFINIDA --}}
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600 space-y-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Função do Usuário</h5>
                                </div>
                                
                                <div class="space-y-3">
                                    @foreach($predefinedRoles as $roleKey => $roleData)
                                        <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors {{ $selectedRole === $roleKey ? 'bg-purple-50 dark:bg-purple-900/20 border-purple-300 dark:border-purple-600' : '' }}">
                                            <input type="radio" 
                                                   wire:click="selectPredefinedRole('{{ $roleKey }}')"
                                                   name="predefined_role"
                                                   value="{{ $roleKey }}"
                                                   {{ $selectedRole === $roleKey ? 'checked' : '' }}
                                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600">
                                            <div class="ml-3 flex-1">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $roleData['name'] }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $roleData['description'] }}</div>
                                                <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">{{ count($roleData['permissions']) }} permissões</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- SEÇÃO DE PERMISSÕES --}}
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600 space-y-4" x-show="$wire.selectedRole !== 'admin'" x-transition>
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Permissões Específicas</h5>
                                </div>
                                
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 max-h-64 overflow-y-auto">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($permissions as $permission)
                                            <label class="flex items-center p-3 hover:bg-white dark:hover:bg-gray-700 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-600 {{ in_array($permission->name, $selectedPermissions) ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-600' : '' }}">
                                                <input type="checkbox" 
                                                       wire:model="selectedPermissions" 
                                                       value="{{ $permission->name }}" 
                                                       {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 dark:border-gray-600 rounded">
                                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="text-xs text-gray-500 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Selecione as permissões específicas para este usuário</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- MENSAGEM PARA ADMIN --}}
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600 space-y-4" x-show="$wire.selectedRole === 'admin'" x-transition>
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Acesso Total</h5>
                                </div>
                                
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <h6 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-1">Administrador do Sistema</h6>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">Este usuário terá acesso completo a todas as funcionalidades do sistema, incluindo gerenciamento de usuários, permissões e configurações avançadas.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- SEÇÃO DE CONFIRMAÇÃO DE SENHA --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mx-6">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Confirmação de Segurança</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-3">Por motivos de segurança, digite sua senha para confirmar as alterações de permissões.</p>
                            <div class="space-y-2">
                                <label for="passwordConfirmation" class="block text-sm font-medium text-yellow-800 dark:text-yellow-200">Sua senha atual:</label>
                                <input type="password" 
                                       wire:model="passwordConfirmation" 
                                       id="passwordConfirmation"
                                       placeholder="Digite sua senha para confirmar"
                                       class="block w-full px-3 py-2 border border-yellow-300 dark:border-yellow-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-yellow-500 dark:placeholder-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                @error('passwordConfirmation') 
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p> 
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RODAPÉ DO MODAL --}}
                <div class="flex-shrink-0 bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-end gap-3">
                        <button wire:click="closePermissionsModal" class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </button>
                        <button wire:click="updatePermissions" class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg transition-all duration-200 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!passwordConfirmation">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Permissões
                        </button>
                    </div>
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
