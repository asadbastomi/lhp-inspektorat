<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'temuan_id',
        'rincian',
        'besaran_temuan',
    ];

    public function temuan()
    {
        return $this->belongsTo(Temuan::class);
    }

    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class);
    }
}
