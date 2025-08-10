<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            // Pimpinan
            'Inspektur',
            'Sekretaris',
            
            // Sekretariat - Kasubbag Perencanaan dan Keuangan
            'Kasubbag Perencanaan dan Keuangan',
            'Fasilitator Pemerintahan',
            'Pengolah Data dan Informasi (Bendahara)',
            
            // Sekretariat - Kasubbag Umum dan Kepegawaian
            'Kasubbag Umum dan Kepegawaian',
            'Fasilitator Pemerintahan',
            'Pengolah Data dan Informasi',
            'Pengadministrasi Perkantoran',
            
            // Support staff positions
            'Petugas Kebersihan',
            'Satpam',
            'Pramubhakti',
            'Sopir',
            'Tenaga Informasi dan Tekhnologi',
            
            // Jabatan Fungsional
            'Irban Wilayah II',
            'PPUPD Madya',
            'Auditor Madya',
            'Auditor Muda',
            'Auditor Pertama',
            'PPUPD Pertama',
            'Irban Wilayah I',
            'PPUPD Muda',
            'Irban Wilayah III',
            'Irban Khusus',
        ];
        
        // Remove duplicates while preserving array keys
        $uniqueJabatans = array_unique($jabatans);

        // Insert all unique positions
        foreach ($uniqueJabatans as $jabatan) {
            DB::table('jabatans')->insert([
                'id' => Str::uuid(),
                'jabatan' => $jabatan,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
