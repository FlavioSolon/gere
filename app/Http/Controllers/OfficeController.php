<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OfficeController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $office = $user->office;

        return Inertia::render('Office/Dashboard', [
            'office' => $office ? [
                'razao_social' => $office->razao_social,
                'cnpj' => $office->cnpj,
                'balance' => $office->balance,
                'subscription_cnpjs' => $office->subscription_cnpjs,
            ] : null,
            'clients' => $office ? $office->clients()->get(['id', 'cnpj', 'razao_social'])->toArray() : [],
            'error' => $office ? null : 'Nenhum escritório associado ao usuário. Contate o suporte.',
        ]);
    }

    public function storeClient(Request $request)
    {
        $user = auth()->user();
        $office = $user->office;

        if (!$office) {
            return redirect()->route('dashboard')->with('error', 'Nenhum escritório associado ao usuário.');
        }

        $validated = $request->validate([
            'cnpj' => 'required|string|unique:clients,cnpj,NULL,id,office_id,' . $office->id,
            'razao_social' => 'required|string|max:255',
        ]);

        $office->clients()->create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'cnpj' => $validated['cnpj'],
            'razao_social' => $validated['razao_social'],
            'office_id' => $office->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Cliente adicionado com sucesso!');
    }
}
