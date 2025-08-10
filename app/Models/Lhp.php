<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PerintahTugas;

class Lhp extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal_lhp',
        'nomor_lhp',
        'judul_lhp',
        'nomor_surat_tugas',
        'tanggal_penugasan',
        'lama_penugasan',
        'user_id',
        'file_lhp',
        'file_surat_tugas',
        'file_kertas_kerja',
        'file_review_sheet',
        'file_nota_dinas',
        'temuan',
        'rincian_rekomendasi',
        'besaran_temuan',
        'tindak_lanjut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lhp' => 'date',
        'tanggal_penugasan' => 'date',
    ];

    /**
     * Get the user that owns the LHP.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tindak lanjut records for the LHP.
     */
    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class);
    }
    /**
     * Get the tim records for the LHP.
     */
    public function tim()
    {
        return $this->hasMany(PerintahTugas::class);
    }

    /**
     * Get all pegawais for the LHP.
     */
    public function pegawais()
    {
        return $this->hasManyThrough(
            Pegawai::class,
            PerintahTugas::class,
            'lhp_id',
            'id',
            'id',
            'pegawai_id'
        );
    }
}
