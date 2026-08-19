<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@berita.local'],
            [
                'name' => 'Super Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
            ]
        );
        $admin->syncRoles(['Admin']);

        $editor = User::updateOrCreate(
            ['email' => 'editor@berita.local'],
            [
                'name' => 'Senior Editor',
                'username' => 'editor',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
            ]
        );
        $editor->syncRoles(['Editor']);

        $penulis = User::updateOrCreate(
            ['email' => 'penulis@berita.local'],
            [
                'name' => 'Jurnalis Berita',
                'username' => 'penulis',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
            ]
        );
        $penulis->syncRoles(['Penulis']);

        $pembaca = User::updateOrCreate(
            ['email' => 'pembaca@berita.local'],
            [
                'name' => 'Pembaca Setia',
                'username' => 'pembaca',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
            ]
        );
        $pembaca->syncRoles(['Pembaca']);
    }
}
