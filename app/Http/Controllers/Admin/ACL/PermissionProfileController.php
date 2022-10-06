<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Models\Profile;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionProfileController extends Controller
{
    protected $profile, $permission;

    public function __construct(Profile $profile, Permission $permission)
    {
        $this->profile = $profile;
        $this->permission = $permission;

        $this->middleware('can:profiles');
    }

    // lista as permissoes de um perfil
    public function permissions($idProfile) 
    {
        $profile = $this->profile->find($idProfile);
        
        if (!$profile) {
            return redirect()->back();
        }

        $permissions = $profile->permissions()->paginate();

        return view('admin.pages.profiles.permissions.permissions', compact('profile', 'permissions'));
    }

    // lista as permissoes disponiveis para serem vinculadas a um perfil
    // esse metodo tambem é chamado ao filtrar as permissoes
    public function permissionsAvailable(Request $request, $idProfile)
    {
        if (!$profile = $this->profile->find($idProfile)) {
            return redirect()->back();
        }

        $filters = $request->except('_token');

        // só exibe as permissoes que ainda não estao ligadas ao perfil
        $permissions = $profile->permissionsAvailable($request->filter);

        return view('admin.pages.profiles.permissions.available', compact('profile', 'permissions', 'filters'));
    }

    public function attachPermissionsProfile(Request $request, $idProfile)
    {
        if (!$profile = $this->profile->find($idProfile)) {
            return redirect()->back();
        }

        // verifica se selecionou algo
        if (!$request->permissions || count($request->permissions) == 0) {
            return redirect()
                ->back()
                ->with('info', 'Pelo menos uma permissão deve ser selecionada');
        }

        // cada item selecionado na tabela vai para um array
        $profile->permissions()->attach($request->permissions);
        
        return redirect()->route('profiles.permissions', $profile->id);
    }

    public function detachPermissionProfile($idProfile, $idPermission)
    {
        $profile = $this->profile->find($idProfile);
        $permission = $this->permission->find($idPermission);

        if (!$profile || !$permission) {
            return redirect()->back();
        }

        $profile->permissions()->detach($permission);
        
        return redirect()->route('profiles.permissions', $profile->id);
    }

    // lista os perfis vinculados a uma permissão
    public function profiles($idPermission) 
    {
        $permission = $this->permission->find($idPermission);
        
        if (!$permission) {
            return redirect()->back();
        }

        $profiles = $permission->profiles()->paginate();

        return view('admin.pages.permissions.profiles.profiles', compact('profiles', 'permission'));
    }
}
