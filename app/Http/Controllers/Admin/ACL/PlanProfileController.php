<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Models\Plan;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanProfileController extends Controller
{
    protected $plan, $profile;

    public function __construct(Plan $plan, Profile $profile)
    {
        $this->plan = $plan;
        $this->profile = $profile;

        $this->middleware('can:plans');
    }

    // lista os perfis de um plano
    public function profiles($idPlan) 
    {
        $plan = $this->plan->find($idPlan);
        
        if (!$plan) {
            return redirect()->back();
        }

        $profiles = $plan->profiles()->paginate();

        return view('admin.pages.plans.profiles.profiles', compact('plan', 'profiles'));
    }

    // lista os perfis disponiveis para serem vinculadas a um plano
    // esse metodo tambem é chamado ao filtrar os perfis
    public function profilesAvailable(Request $request, $idPlan)
    {
        if (!$plan = $this->plan->find($idPlan)) {
            return redirect()->back();
        }

        $filters = $request->except('_token');

        // só exibe os perfis que ainda não estao ligadas ao plano
        $profiles = $plan->profilesAvailable($request->filter);

        return view('admin.pages.plans.profiles.available', compact('plan', 'profiles', 'filters'));
    }

    public function attachProfilesPlan(Request $request, $idPlan)
    {
        if (!$plan = $this->plan->find($idPlan)) {
            return redirect()->back();
        }

        // verifica se selecionou algo
        if (!$request->profiles || count($request->profiles) == 0) {
            return redirect()
                ->back()
                ->with('info', 'Pelo menos um perfil deve ser selecionado');
        }

        // cada item selecionado na tabela vai para um array
        $plan->profiles()->attach($request->profiles);
        
        return redirect()->route('plans.profiles', $plan->id);
    }

    public function detachProfilePlan($idPlan, $idProfile)
    {
        $plan = $this->plan->find($idPlan);
        $profile = $this->profile->find($idProfile);

        if (!$plan || !$profile) {
            return redirect()->back();
        }

        $plan->profiles()->detach($profile);
        
        return redirect()->route('plans.profiles', $plan->id);
    }

    // lista os planos vinculados a um perfil
    public function plans($idProfile) 
    {
        $profile = $this->profile->find($idProfile);
        
        if (!$profile) {
            return redirect()->back();
        }

        $plans = $profile->plans()->paginate();

        return view('admin.pages.profiles.plans.plans', compact('plans', 'profile'));
    }
}
