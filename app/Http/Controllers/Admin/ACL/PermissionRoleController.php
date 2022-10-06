<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionRoleController extends Controller
{
    protected $role, $permission;

    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;

        $this->middleware('can:roles');
    }

    // lista as permissoes de um cargo
    public function permissions($idRole) 
    {
        $role = $this->role->find($idRole);
        
        if (!$role) {
            return redirect()->back();
        }

        $permissions = $role->permissions()->paginate();

        return view('admin.pages.roles.permissions.permissions', compact('role', 'permissions'));
    }

    // lista as permissoes disponiveis para serem vinculadas a um cargo
    // esse metodo tambem é chamado ao filtrar as permissoes
    public function permissionsAvailable(Request $request, $idRole)
    {
        if (!$role = $this->role->find($idRole)) {
            return redirect()->back();
        }

        $filters = $request->except('_token');

        // só exibe as permissoes que ainda não estao ligadas ao cargo
        $permissions = $role->permissionsAvailable($request->filter);

        return view('admin.pages.roles.permissions.available', compact('role', 'permissions', 'filters'));
    }

    public function attachPermissionsRole(Request $request, $idRole)
    {
        if (!$role = $this->role->find($idRole)) {
            return redirect()->back();
        }

        // verifica se selecionou algo
        if (!$request->permissions || count($request->permissions) == 0) {
            return redirect()
                ->back()
                ->with('info', 'Pelo menos uma permissão deve ser selecionada');
        }

        // cada item selecionado na tabela vai para um array
        $role->permissions()->attach($request->permissions);
        
        return redirect()->route('roles.permissions', $role->id);
    }

    public function detachPermissionRole($idRole, $idPermission)
    {
        $role = $this->role->find($idRole);
        $permission = $this->permission->find($idPermission);

        if (!$role || !$permission) {
            return redirect()->back();
        }

        $role->permissions()->detach($permission);
        
        return redirect()->route('roles.permissions', $role->id);
    }

    // lista os cargos vinculados a uma permissão
    public function roles($idPermission) 
    {
        $permission = $this->permission->find($idPermission);
        
        if (!$permission) {
            return redirect()->back();
        }

        $roles = $permission->roles()->paginate();

        return view('admin.pages.permissions.roles.roles', compact('roles', 'permission'));
    }
}
