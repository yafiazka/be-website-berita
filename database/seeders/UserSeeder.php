<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role dan permissions telah terdaftar
        $this->call(RolePermissionSeeder::class);

        $users = [
            // ── ADMINISTRATOR ──────────────────────────────────────────
            [
                'name' => 'Super Administrator',
                'username' => 'admin',
                'email' => 'admin@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Admin',
            ],

            // ── EDITORS (Meja Redaksi) ─────────────────────────────────
            [
                'name' => 'Senior Editor Redaksi',
                'username' => 'editor',
                'email' => 'editor@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Editor',
            ],
            [
                'name' => 'Dewi Anggraini (Editor Bisnis)',
                'username' => 'dewi.editor',
                'email' => 'dewi@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Editor',
            ],

            // ── PENULIS / JURNALIS ─────────────────────────────────────
            [
                'name' => 'Jurnalis Berita Utama',
                'username' => 'penulis',
                'email' => 'penulis@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Penulis',
            ],
            [
                'name' => 'Budi Santoso (Koresponden IKN)',
                'username' => 'budi.jurnalis',
                'email' => 'budi@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Penulis',
            ],
            [
                'name' => 'Rian Hidayat (Jurnalis Teknologi)',
                'username' => 'rian.hidayat',
                'email' => 'rian@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Penulis',
            ],

            // ── PEMBACA (Komunitas Pembaca Terdaftar) ───────────────────
            [
                'name' => 'Pembaca Setia',
                'username' => 'pembaca',
                'email' => 'pembaca@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Pembaca',
            ],
            [
                'name' => 'Andi Prasetyo',
                'username' => 'andi.pembaca',
                'email' => 'andi@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Pembaca',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'username' => 'siti.nurhaliza',
                'email' => 'siti@berita.local',
                'password' => Hash::make('password'),
                'is_active' => true,
                'avatar' => null,
                'role' => 'Pembaca',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->syncRoles([$role]);
        }
    }
}
