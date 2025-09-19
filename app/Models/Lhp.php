<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PerintahTugas;
use App\Models\Temuan;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Lhp extends Model
{
    use HasFactory, HasUuids, HasRelationships;

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
        'tgl_surat_tugas',
        'tgl_awal_penugasan',
        'tgl_akhir_penugasan',
        'user_id',
        'file_lhp',
        'file_surat_tugas',
        'file_kertas_kerja',
        'file_review_sheet',
        'file_nota_dinas',
        'file_p2hp',
        'tindak_lanjut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lhp' => 'date',
        'tgl_surat_tugas' => 'date',
        'tgl_awal_penugasan' => 'date',
        'tgl_akhir_penugasan' => 'date',
    ];

    /**
     * Get the user that owns the LHP.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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

    /**
     * Get the temuan records for the LHP.
     */
    public function temuans()
    {
        return $this->hasMany(Temuan::class);
    }

    public function tindakLanjuts()
    {
        return $this->hasManyDeep(
            TindakLanjut::class,
            [Temuan::class, Rekomendasi::class],
        );
    }
}
