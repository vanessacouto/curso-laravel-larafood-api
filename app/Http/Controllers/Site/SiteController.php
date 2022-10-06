<?php

namespace App\Http\Controllers\Site;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function index()
    {
        // traz os planos e seus detalhes
        $plans = Plan::with('details')
            ->orderBy('price', 'ASC')
            ->get();

        return view('site.pages.home.index', compact('plans'));
    }

    public function plan($url)
    {
        // recupera o plano pela url
        if(!$plan = Plan::where('url', $url)->first()) {
            // se não encontrar o plano
            return redirect()->back();
        }

        // cria uma sessao com o plano escolhido no 'site'
        session()->put('plan', $plan);

        // redireciona para a rota onde a pessoa vai se cadastrar no sistema
        return redirect()->route('register');
    }
}
