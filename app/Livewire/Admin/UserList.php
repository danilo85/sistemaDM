<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserList extends Component
{
    use WithPagination;

    // Propriedade para busca
    public string $search = '';

    // Propriedades para o modal de permissões
    public ?User $managingPermissionsForUser = null;
    public array $userPermissions = [];
    public string $passwordConfirmation = '';
    public bool $showPermissionsModal = false;
    public string $selectedRole = '';
    public array $predefinedRoles = [];
    public array $selectedPermissions = [];

    // Propriedades para o modal de exclusão
    public ?User $deletingUser = null;
    public string $deletePasswordConfirmation = '';

    public function mount()
    {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
        $this->setupPredefinedRoles();
    }

    private function setupPredefinedRoles()
    {
        $this->predefinedRoles = [
            'admin' => [
                'name' => 'Admin',
                'description' => 'Acesso completo ao sistema',
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            'user' => [
                'name' => 'User',
                'description' => 'Usuário com acesso apenas a orçamentos',
                'permissions' => ['acessar_orcamentos']
            ]
        ];
    }

    public function openPermissionsModal(User $user)
    {
        $this->managingPermissionsForUser = $user;
        $this->userPermissions = $user->permissions->pluck('name')->toArray();
        $this->selectedPermissions = $user->permissions->pluck('name')->toArray();
        $this->selectedRole = $user->getRoleNames()->first() ?? '';
        $this->showPermissionsModal = true;
        $this->reset('passwordConfirmation');
        $this->resetErrorBag('passwordConfirmation');
    }

    public function selectPredefinedRole($roleKey)
    {
        if (isset($this->predefinedRoles[$roleKey])) {
            $this->selectedRole = $roleKey;
            
            // Para admin, definir todas as permissões automaticamente
            if ($roleKey === 'admin') {
                $this->selectedPermissions = Permission::all()->pluck('name')->toArray();
            } else {
                // Para outros roles, usar as permissões predefinidas
                $this->selectedPermissions = $this->predefinedRoles[$roleKey]['permissions'];
            }
            
            // Disparar evento para atualizar a interface
            $this->dispatch('role-selected', $roleKey);
        }
    }

    public function closePermissionsModal()
    {
        $this->managingPermissionsForUser = null;
        $this->showPermissionsModal = false;
    }

    public function openDeleteModal(User $user)
    {
        $this->deletingUser = $user;
        $this->reset('deletePasswordConfirmation');
        $this->resetErrorBag('deletePasswordConfirmation');
    }

    public function closeDeleteModal()
    {
        $this->deletingUser = null;
    }

    public function updatePermissions()
    {
        $this->validate(['passwordConfirmation' => 'required'], ['passwordConfirmation.required' => 'A confirmação de senha é obrigatória.']);

        if (! Hash::check($this->passwordConfirmation, Auth::user()->password)) {
            $this->addError('passwordConfirmation', 'A senha está incorreta.');
            return;
        }

        if ($this->managingPermissionsForUser) {
            try {
                DB::transaction(function () {
                    // Log para debug
                    \Log::info('Atualizando permissões para usuário: ' . $this->managingPermissionsForUser->id);
                    \Log::info('Role selecionada: ' . $this->selectedRole);
                    \Log::info('Permissões selecionadas: ' . json_encode($this->selectedPermissions));
                    
                    // Primeiro, remover todas as roles e permissões existentes
                    $this->managingPermissionsForUser->syncRoles([]);
                    $this->managingPermissionsForUser->syncPermissions([]);
                    
                    // Aplicar a nova role se selecionada
                    if ($this->selectedRole) {
                        // Verificar se a role existe, se não, criar
                        $role = Role::firstOrCreate(['name' => $this->selectedRole]);
                        $this->managingPermissionsForUser->assignRole($role);
                        \Log::info('Role atribuída: ' . $this->selectedRole);
                    }
                    
                    // Aplicar permissões específicas
                    if (!empty($this->selectedPermissions)) {
                        // Verificar se todas as permissões existem
                        $existingPermissions = Permission::whereIn('name', $this->selectedPermissions)->pluck('name')->toArray();
                        $this->managingPermissionsForUser->givePermissionTo($existingPermissions);
                        \Log::info('Permissões aplicadas: ' . json_encode($existingPermissions));
                    }
                    
                    // Recarregar o usuário
                    $this->managingPermissionsForUser->refresh();
                    $this->managingPermissionsForUser->load('roles', 'permissions');
                });
                
                \Log::info('Permissões finais: ' . json_encode($this->managingPermissionsForUser->permissions->pluck('name')->toArray()));
                \Log::info('Roles finais: ' . json_encode($this->managingPermissionsForUser->roles->pluck('name')->toArray()));
                
                $this->dispatch('notify', 'Permissões atualizadas com sucesso!');
                $this->closePermissionsModal();
            } catch (\Exception $e) {
                \Log::error('Erro ao atualizar permissões: ' . $e->getMessage());
                $this->dispatch('notify-error', 'Erro ao atualizar permissões: ' . $e->getMessage());
            }
        }
    }

    public function confirmDeleteUser()
    {
        $this->validate(['deletePasswordConfirmation' => 'required'], ['deletePasswordConfirmation.required' => 'A confirmação de senha é obrigatória.']);

        if (! Hash::check($this->deletePasswordConfirmation, Auth::user()->password)) {
            $this->addError('deletePasswordConfirmation', 'A senha está incorreta.');
            return;
        }

        if ($this->deletingUser) {
            if ($this->deletingUser->id === Auth::id()) {
                $this->dispatch('notify-error', 'Você não pode excluir a si mesmo.');
                $this->closeDeleteModal();
                return;
            }

            // Inicia uma transação para garantir que tudo seja excluído ou nada seja.
            DB::transaction(function () {
                // Deleta todos os dados relacionados ao usuário
                $this->deletingUser->transacoes()->delete();
                $this->deletingUser->orcamentos()->delete();
                $this->deletingUser->clientes()->delete();
                $this->deletingUser->autores()->delete();
                $this->deletingUser->categorias()->delete();
                $this->deletingUser->bancos()->delete();
                $this->deletingUser->cartoesCredito()->delete();
                $this->deletingUser->receipts()->delete();
                
                // Finalmente, deleta o usuário
                $this->deletingUser->delete();
            });

            $this->dispatch('notify', 'Usuário e todos os seus dados foram excluídos com sucesso!');
            $this->closeDeleteModal();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('roles', 'permissions');
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        $users = $query->orderBy('last_seen_at', 'desc')->paginate(10);
        
        $allPermissions = Permission::all()->groupBy(function ($permission) {
            return explode('_', $permission->name)[1] ?? 'geral';
        });

        return view('livewire.admin.user-list', [
            'users' => $users,
            'allPermissions' => $allPermissions,
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }
}