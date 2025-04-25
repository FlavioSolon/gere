<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Obter o escritório
        $office = Office::where('cnpj', '12.345.678/0001-90')->first();

        if (!$office) {
            Log::error('Office not found for CNPJ: 12.345.678/0001-90');
            throw new \Exception('Office not found. Please run OfficeSeeder first.');
        }

        Log::info('Using Office ID: ' . $office->id);

        // Criar usuário office
        User::firstOrCreate(
            ['email' => 'solondutra73+office@gmail.com'],
            [
                'id' => (string) Str::uuid(),
                'password' => bcrypt('password123'),
                'role' => 'office',
                'office_id' => $office->id,
            ]
        );

        // Criar usuário admin
        User::firstOrCreate(
            ['email' => 'solondutra73+admin@gmail.com'],
            [
                'id' => (string) Str::uuid(),
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'office_id' => null,
            ]
        );
    }
}
