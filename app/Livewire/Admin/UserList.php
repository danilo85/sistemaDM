<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class UserList extends Component
{
    use WithPagination;

    // Propriedades para o modal de permissões
    public ?User $managingPermissionsForUser = null;
    public array $userPermissions = [];
    public string $passwordConfirmation = '';

    // Propriedades para o modal de exclusão
    public ?User $deletingUser = null;
    public string $deletePasswordConfirmation = '';

    public function openPermissionsModal(User $user)
    {
        $this->managingPermissionsForUser = $user;
        $this->userPermissions = $user->permissions->pluck('name')->toArray();
        $this->reset('passwordConfirmation');
        $this->resetErrorBag('passwordConfirmation');
    }

    public function closePermissionsModal()
    {
        $this->managingPermissionsForUser = null;
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

    public function updateUserPermissions()
    {
        $this->validate(['passwordConfirmation' => 'required'], ['passwordConfirmation.required' => 'A confirmação de senha é obrigatória.']);

        if (! Hash::check($this->passwordConfirmation, Auth::user()->password)) {
            $this->addError('passwordConfirmation', 'A senha está incorreta.');
            return;
        }

        if ($this->managingPermissionsForUser) {
            $this->managingPermissionsForUser->syncPermissions($this->userPermissions);
            $this->dispatch('notify', 'Permissões atualizadas com sucesso!');
            $this->closePermissionsModal();
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

    public function render()
    {
        $users = User::with('roles', 'permissions')->orderBy('last_seen_at', 'desc')->paginate(10);
        
        $allPermissions = Permission::all()->groupBy(function ($permission) {
            return explode('_', $permission->name)[1] ?? 'geral';
        });

        return view('livewire.admin.user-list', [
            'users' => $users,
            'allPermissions' => $allPermissions,
        ]);
    }
}