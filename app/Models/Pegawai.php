<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'jabatan_id',
        'pangkat_id',
        'golongan_id',
        'is_plt',
        'plt_start_date',
        'plt_end_date',
        'plt_sk_number',
    ];

    protected $casts = [
        'is_plt' => 'boolean',
        'plt_start_date' => 'date',
        'plt_end_date' => 'date',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
