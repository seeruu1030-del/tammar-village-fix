<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Block;

class BlockSeeder extends Seeder
{
    public function run(): void
    {
        Block::create(['name' => 'A', 'description' => 'Blok Utama', 'total_units' => 50]);
        Block::create(['name' => 'B', 'description' => 'Blok Selatan', 'total_units' => 40]);
        Block::create(['name' => 'C', 'description' => 'Blok Timur', 'total_units' => 60]);
        Block::create(['name' => 'D', 'description' => 'Blok Barat', 'total_units' => 30]);
    }
}
