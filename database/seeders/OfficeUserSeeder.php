<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OfficeUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'solondutra73@gmail.com';
        $password = 'password';
        $role = 'office';

        // Verifica se o usuário já existe
        $user = User::where('email', $email)->first();

        if ($user) {
            // Atualiza role se necessário
            if ($user->role !== $role) {
                $user->update(['role' => $role]);
                $this->command->info("Role atualizado para 'office' para o usuário: {$email}");
            }

            // Verifica se já tem um escritório associado
            if (!$user->office) {
                $office = Office::create([
                    'id' => (string) Str::uuid(),
                    'cnpj' => '98.765.432/0001-21',
                    'razao_social' => 'Contabilidade Solon LTDA',
                    'balance' => 100000, // Exemplo: R$ 1000,00 (em centavos)
                    'subscription_cnpjs' => 10,
                ]);

                $user->update(['office_id' => $office->id]);
                $this->command->info("Novo escritório associado ao usuário: {$email}");
            } else {
                $this->command->info("Usuário {$email} já possui um escritório associado.");
            }
        } else {
            // Cria novo usuário
            $office = Office::create([
                'id' => (string) Str::uuid(),
                'cnpj' => '98.765.432/0001-21',
                'razao_social' => 'Contabilidade Solon LTDA',
                'balance' => 100000,
                'subscription_cnpjs' => 10,
            ]);

            $user = User::create([
                'name' => 'Solon Dutra',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'office_id' => $office->id,
                'email_verified_at' => now(),
            ]);

            $this->command->info("Usuário {$email} criado e associado ao escritório.");
        }
    }
}
