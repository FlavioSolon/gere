<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function createOffice()
    {
        return Inertia::render('Admin/Offices/Create');
    }

    public function storeOffice(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|unique:offices,cnpj',
            'razao_social' => 'required|string|max:255',
            'certificate' => 'required|file|mimes:pfx|max:2048',
            'certificate_password' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            $certificatePath = $request->file('certificate')->store('certificates', 'private');

            $office = Office::create([
                'cnpj' => $request->cnpj,
                'razao_social' => $request->razao_social,
                'certificate_path' => $certificatePath,
                'certificate_password' => $request->certificate_password,
                'balance' => 0,
                'subscription_cnpjs' => 0,
            ]);

            User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'office',
                'office_id' => $office->id,
            ]);
        });

        return redirect()->route('admin.index')->with('success', 'Escritório criado com sucesso!');
    }
}
