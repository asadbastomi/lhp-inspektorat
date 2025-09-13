<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temuan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'lhp_id',
        'jenis_pengawasan',
        'rincian',
        'penyebab',
    ];

    public static $jenisPengawasanOptions = [
        'Audit Kinerja',
        'Audit Ketaatan',
        'Audit Tujuan Tertentu',
        'Audit Investigasi',
        'Probity Audit',
        'Reviu Pengelolaan Keuangan',
        'Reviu Dokumen Perencanaan',
        'Evaluasi',
        'Pemantauan',
        'Penugasan Lainnya',
    ];

    public function lhp()
    {
        return $this->belongsTo(Lhp::class);
    }

    public function rekomendasis()
    {
        return $this->hasMany(Rekomendasi::class);
    }
}
