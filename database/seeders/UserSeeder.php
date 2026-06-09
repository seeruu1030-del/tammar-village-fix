<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Budi Admin',
            'username' => 'admin',
            'email' => 'admin@tammar.id',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@example.com',
            'role' => 'warga',
            'password' => Hash::make('budi123'),
        ]);

        User::create([
            'name' => 'Petugas Bank',
            'username' => 'bank',
            'email' => 'bank@example.com',
            'role' => 'bank',
            'password' => Hash::make('bank123'),
        ]);

        User::create([
            'name' => 'Petugas Keamanan',
            'username' => 'security',
            'email' => 'security@example.com',
            'role' => 'security',
            'password' => Hash::make('security123'),
        ]);
    }
}
