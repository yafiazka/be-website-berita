<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Artificial Intelligence', 'slug' => 'ai'],
            ['name' => 'Startup', 'slug' => 'startup'],
            ['name' => 'Gadget', 'slug' => 'gadget'],
            ['name' => 'Politik', 'slug' => 'politik'],
            ['name' => 'Finansial', 'slug' => 'finansial'],
            ['name' => 'Wisata', 'slug' => 'wisata'],
            ['name' => 'Kuliner', 'slug' => 'kuliner'],
            ['name' => 'Pemerintah', 'slug' => 'pemerintah'],
            ['name' => 'Investasi', 'slug' => 'investasi'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['slug' => $tag['slug']], $tag);
        }
    }
}
