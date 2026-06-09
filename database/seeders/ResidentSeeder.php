<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\Block;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $blockB = Block::where('name', 'B')->first();
        Resident::create([
            'name' => 'Budi Santoso',
            'nik' => '3273123456780001',
            'family_status' => 'KK',
            'age' => 42,
            'contact' => '0812-3456-7890',
            'block_id' => $blockB->id,
            'unit_no' => '12',
            'housing_status' => 'Owner',
            'status' => 'active'
        ]);

        $blockA = Block::where('name', 'A')->first();
        Resident::create([
            'name' => 'Ahmad Fauzi',
            'nik' => '3578234567890003',
            'family_status' => 'KK',
            'age' => 38,
            'contact' => '0811-2233-4455',
            'block_id' => $blockA->id,
            'unit_no' => '01',
            'housing_status' => 'Owner',
            'status' => 'active'
        ]);
    }
}
