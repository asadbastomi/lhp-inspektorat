<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JabatanTim;

class JabatanTimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            'Penanggung Jawab',
            'Koordinator',
            'Ketua Tim',
            'Anggota Tim',
            'Pengendali Teknis'
        ];

        foreach ($jabatans as $jabatan) {
            JabatanTim::updateOrCreate(
                ['nama' => $jabatan],
            );
        }
    }
}
