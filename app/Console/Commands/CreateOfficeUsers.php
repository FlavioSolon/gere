<?php

namespace App\Console\Commands;

use App\Models\Office;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateOfficeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create-offices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create two office users with predefined emails and associated offices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $officesData = [
            [
                'cnpj' => '12.345.678/0001-90',
                'razao_social' => 'Contabilidade Teste LTDA',
                'certificate_path' => 'certificates/teste1.pfx',
                'certificate_password' => 'senha123',
                'balance' => 100000,
                'subscription_cnpjs' => 5,
            ],
            [
                'cnpj' => '98.765.432/0001-09',
                'razao_social' => 'Escritório Exemplo S/A',
                'certificate_path' => 'certificates/exemplo2.pfx',
                'certificate_password' => 'senha456',
                'balance' => 150000,
                'subscription_cnpjs' => 10,
            ],
        ];

        $users = [
            [
                'email' => 'solondutra73+office@gmail.com',
                'password' => 'password123',
                'role' => 'office',
                'cnpj' => '12.345.678/0001-90', // Relacionado ao primeiro escritório
            ],
            [
                'email' => 'judsonscutrim+office@gmail.com',
                'password' => 'password456',
                'role' => 'office',
                'cnpj' => '98.765.432/0001-09', // Relacionado ao segundo escritório
            ],
        ];

        foreach ($officesData as $officeData) {
            // Gerar UUID para o escritório
            $uuid = (string) Str::uuid();
            Log::info('Generated UUID for Office: ' . $uuid);

            // Criar ou recuperar o escritório
            $office = Office::firstOrCreate(
                ['cnpj' => $officeData['cnpj']],
                [
                    'id' => $uuid,
                    'razao_social' => $officeData['razao_social'],
                    'certificate_path' => $officeData['certificate_path'],
                    'certificate_password' => $officeData['certificate_password'],
                    'balance' => $officeData['balance'],
                    'subscription_cnpjs' => $officeData['subscription_cnpjs'],
                ]
            );

            if ($office->wasRecentlyCreated) {
                $this->info("Office {$officeData['razao_social']} (CNPJ: {$officeData['cnpj']}) created successfully!");
            } else {
                $this->info("Office {$officeData['razao_social']} (CNPJ: {$officeData['cnpj']}) already exists!");
            }
        }

        foreach ($users as $user) {
            // Encontrar o escritório associado
            $office = Office::where('cnpj', $user['cnpj'])->first();

            if (!$office) {
                $this->error("Office with CNPJ {$user['cnpj']} not found for user {$user['email']}!");
                continue;
            }

            // Verificar se o usuário já existe
            if (User::where('email', $user['email'])->exists()) {
                $this->info("User {$user['email']} already exists!");
                continue;
            }

            // Criar o usuário
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'id' => (string) Str::uuid(),
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                    'office_id' => $office->id,
                ]
            );

            $this->info("Office user {$user['email']} created successfully and associated with office {$office->razao_social}!");
        }

        $this->info('Office users creation process completed!');
    }
}
