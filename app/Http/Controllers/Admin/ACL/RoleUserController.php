<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleUserController extends Controller
{
    protected $user, $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;

        $this->middleware('can:users');
    }

    // lista os cargos de um usuario
    public function roles($idUser) 
    {
        $user = $this->user->find($idUser);
        
        if (!$user) {
            return redirect()->back();
        }

        $roles = $user->roles()->paginate();

        return view('admin.pages.users.roles.roles', compact('user', 'roles'));
    }

    // lista os cargos disponiveis para serem vinculadas a um usuario
    // esse metodo tambem é chamado ao filtrar os cargos
    public function rolesAvailable(Request $request, $idUser)
    {
        if (!$user = $this->user->find($idUser)) {
            return redirect()->back();
        }

        $filters = $request->except('_token');

        // só exibe os cargos que ainda não estao ligadas ao usuario
        $roles = $user->rolesAvailable($request->filter);

        return view('admin.pages.users.roles.available', compact('user', 'roles', 'filters'));
    }

    public function attachRolesUser(Request $request, $idUser)
    {
        if (!$user = $this->user->find($idUser)) {
            return redirect()->back();
        }

        // verifica se selecionou algo
        if (!$request->roles || count($request->roles) == 0) {
            return redirect()
                ->back()
                ->with('info', 'Pelo menos um cargo deve ser selecionado');
        }

        // cada item selecionado na tabela vai para um array
        $user->roles()->attach($request->roles);
        
        return redirect()->route('users.roles', $user->id);
    }

    public function detachRoleUser($idUser, $idRole)
    {
        $user = $this->user->find($idUser);
        $role = $this->role->find($idRole);

        if (!$user || !$role) {
            return redirect()->back();
        }

        $user->roles()->detach($role);
        
        return redirect()->route('users.roles', $user->id);
    }

    // lista os usuarios vinculados a um cargo
    public function users($idRole) 
    {
        $role = $this->role->find($idRole);
        
        if (!$role) {
            return redirect()->back();
        }

        $users = $role->users()->paginate();

        return view('admin.pages.roles.users.users', compact('users', 'role'));
    }
}
