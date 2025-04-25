<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create-admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create two admin users with predefined emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = [
            [
                'email' => 'solondutra73@gmail.com',
                'password' => 'password',
                'role' => 'office'
            ],
            [
                'email' => 'judsonscutrim@gmail.com',
                'password' => 'password',
                'role' => 'admin'
            ]
        ];

        foreach ($admins as $admin) {
            // Verifica se o usuário já existe
            if (User::where('email', $admin['email'])->exists()) {
                $this->info("User {$admin['email']} already exists!");
                continue;
            }

            // Cria o usuário
            User::create([
                'email' => $admin['email'],
                'password' => Hash::make($admin['password']),
                'role' => $admin['role'],
            ]);

            $this->info("Admin user {$admin['email']} created successfully!");
        }

        $this->info('Admin users creation process completed!');
    }
}
