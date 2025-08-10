<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golongans = [
            ['kode' => 'IV.c'],
            ['kode' => 'IV.b'],
            ['kode' => 'IV.a'],
            ['kode' => 'III.d'],
            ['kode' => 'III.c'],
            ['kode' => 'III.b'],
            ['kode' => 'III.a'],
            ['kode' => 'II.a'],
        ];

        $data = array_map(function ($golongan) {
            $golongan['id'] = Str::uuid();
            return $golongan;
        }, $golongans);

        DB::table('golongans')->insert($data);
    }
}
