<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@quicksell.com'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'phone' => '+7 (999) 000-00-00',
            ]
        );

        $this->command->info('Администратор создан!');
        $this->command->info('Email: admin@quicksell.com');
        $this->command->info('Пароль: admin123');
    }
}
