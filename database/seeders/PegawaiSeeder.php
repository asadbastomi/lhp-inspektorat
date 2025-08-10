<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('import/pegawai.xlsx');
        $data = Excel::toArray(new \stdClass(), $path);

        if (empty($data) || empty($data[0])) {
            $this->command->warn('Pegawai excel file is empty or not found.');
            return;
        }

        // Get the first sheet
        $rows = $data[0];

        // Skip header row if exists
        $header = array_shift($rows);

        // Process each row as a complete employee record
        // foreach ($rows as $row) {
        //     // Extract values from Excel columns (0-based index)
        //     $nama = $row[2] ?? null;      // Column C (index 2) - Nama
        //     $pangkat = $row[3] ?? null;   // Column D (index 3) - Pangkat
        //     $jabatan = trim($row[4] ?? '');  // Column E (index 4) - Jabatan
        //     $nipRaw = $row[12] ?? '';     // Column M (index 12) - NIP (may contain 'NIP.' prefix)
        //     $golongan = $row[13] ?? null; // Column N (index 13) - Golongan
            
        //     // Extract NIP - remove 'NIP.' prefix if present and get only numbers
        //     $nip = preg_replace('/[^0-9]/', '', $nipRaw);
            
        //     if (empty($nama) || empty($nip)) {
        //         continue; // Skip if essential data is missing
        //     }
            
        //     // Insert the employee record
        //     DB::table('pegawais')->insert([
        //         'id' => Str::uuid(),
        //         'nama' => $nama,
        //         'nip' => $nip,
        //         'jabatan_id' => DB::table('jabatans')->where('jabatan', $jabatan)->value('id'), // You'll need to set this based on your data
        //         'pangkat_id' => $pangkat ? DB::table('pangkats')->where('nama', $pangkat)->value('id') : null,
        //         'golongan_id' => $golongan ? DB::table('golongans')->where('kode', $golongan)->value('id') : null,
        //         'is_plt' => false, // Default to false, update as needed
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
        foreach ($rows as $i => $row) {
            $nipRaw = $row[2] ?? null;
        
            if (!empty($nipRaw)) {
                $nip = preg_replace('/[^0-9]/', '', $nipRaw);
                if (empty($nip)) {
                    continue;
                }
        
                // C12 (nama)
                $nama = trim($rows[$i - 1][2] ?? '');
                // D12 (pangkat)
                $pangkat = trim($rows[$i - 1][3] ?? '');
                // D13 (golongan)
                $golongan = trim($row[3] ?? '');
                $golongan = preg_replace('/[\(\)]/', '', $golongan); // Remove ( and )
                // E12 (jabatan, merged E12:E13)
                $jabatan = trim($rows[$i - 1][4] ?? '');
        
                // Simpan atau proses datanya
                try {
                    Pegawai::updateOrCreate(
                        ['nip' => $nip],
                        [
                            'nama' => $nama,
                            'jabatan_id' => DB::table('jabatans')->where('jabatan', 'like', '%' . $jabatan . '%')->value('id'),
                            'pangkat_id' => $pangkat ? DB::table('pangkats')->where('nama', $pangkat)->value('id') : null,
                            'golongan_id' => $golongan ? DB::table('golongans')->where('kode', 'like', '%' . $golongan . '%')->value('id') : null,
                        ]
                    );
                } catch (\Throwable $e) {
                    dd([
                        'error' => $e->getMessage(),
                        'nip' => $nip,
                        'nama' => $nama,
                        'jabatan' => $jabatan,
                        'pangkat' => $pangkat,
                        'golongan' => $golongan,
                    ]);
                }
            }
        }
    }
}
