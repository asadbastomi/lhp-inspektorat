<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Jabatan;


class Pegawai extends Model
{
    use HasUuids;

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
