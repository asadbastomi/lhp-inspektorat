<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pangkats = [
            'Pembina Utama Muda',
            'Pembina Tk.I',
            'Pembina',
            'Penata Tk.I',
            'Penata',
            'Penata Muda Tk.I',
            'Penata Muda',
            'Pengatur Muda',
        ];

        foreach ($pangkats as $pangkat) {
            DB::table('pangkats')->insert([
                'id' => Str::uuid(),
                'nama' => $pangkat
            ]);
        }
    }
}
