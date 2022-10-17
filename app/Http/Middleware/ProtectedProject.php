<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProtectedProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request                                                                          $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // verifica se esta rodando via console
        if (app()->runningInConsole()) {
            return $next($request);
        }

        // sempre que acessar qualquer pagina do  projeto, vai redirecionar para esse endereço
        return redirect()->away('https://www.especializati.com/curso-laravael-larafood');
    }
}
