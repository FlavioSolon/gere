<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsOffice
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isOffice()) {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a usuários do tipo escritório.');
        }
        return $next($request);
    }
}
