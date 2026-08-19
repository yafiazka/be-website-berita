<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nasional',
                'slug' => 'nasional',
                'description' => 'Berita peristiwa, kebijakan, dan dinamika seputar Indonesia.',
            ],
            [
                'name' => 'Internasional',
                'slug' => 'internasional',
                'description' => 'Kabar terkini dari berbagai belahan dunia dan geopolitik global.',
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Perkembangan AI, gadget, inovasi software, dan dunia digital.',
            ],
            [
                'name' => 'Bisnis & Ekonomi',
                'slug' => 'bisnis',
                'description' => 'Pasar saham, keuangan, startup, dan investasi.',
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'description' => 'Sepak bola, bulu tangkis, motorsport, dan berita atlet terkini.',
            ],
            [
                'name' => 'Gaya Hidup & Hiburan',
                'slug' => 'hiburan',
                'description' => 'Film, musik, tren kesehatan, dan kuliner pilihan.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
