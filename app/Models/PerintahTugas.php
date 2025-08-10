<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class PerintahTugas extends Model
{
    use HasFactory, HasUuids;
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'lhp_id',
        'pegawai_id',
        'jabatan_tim_id'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    
    public function lhp()
    {
        return $this->belongsTo(Lhp::class);
    }
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    
    public function jabatanTim()
    {
        return $this->belongsTo(JabatanTim::class, 'jabatan_tim_id', 'id');
    }
}
