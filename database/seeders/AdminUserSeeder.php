<?php

namespace Database\Seeders;

use App\Core\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sholokhovdaniil@yandex.ru'], // уникальный ключ
            [
                'name' => 'admin',
                'password' => Hash::make('admin'), // зашифрованный пароль
            ]
        );
    }
}
