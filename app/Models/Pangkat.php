<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pangkat extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'nama'
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
