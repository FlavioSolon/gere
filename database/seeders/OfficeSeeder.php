<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $uuid = (string) Str::uuid();
        Log::info('Generated UUID for Office: ' . $uuid);

        $office = Office::firstOrCreate(
            ['cnpj' => '12.345.678/0001-90'],
            [
                'id' => $uuid,
                'razao_social' => 'Contabilidade Teste LTDA',
                'certificate_path' => 'certificates/teste.pfx',
                'certificate_password' => 'senha123',
                'balance' => 100000,
                'subscription_cnpjs' => 5,
            ]
        );

        Log::info('Office created: ' . json_encode([
                'id' => $office->id,
                'cnpj' => $office->cnpj,
                'razao_social' => $office->razao_social,
            ]));
    }
}
