<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@berita.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150',
            ]
        );
        $admin->syncRoles(['Admin']);

        $editor = User::firstOrCreate(
            ['email' => 'editor@berita.local'],
            [
                'name' => 'Senior Editor',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150',
            ]
        );
        $editor->syncRoles(['Editor']);

        $penulis = User::firstOrCreate(
            ['email' => 'penulis@berita.local'],
            [
                'name' => 'Jurnalis Berita',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
            ]
        );
        $penulis->syncRoles(['Penulis']);

        $pembaca = User::firstOrCreate(
            ['email' => 'pembaca@berita.local'],
            [
                'name' => 'Pembaca Setia',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150',
            ]
        );
        $pembaca->syncRoles(['Pembaca']);
    }
}
