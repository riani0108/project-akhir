<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAntenna extends Model
{
    use HasFactory;

    protected $table = 'data_antenna';
    protected $fillable = [
        'jenis_antenna',
        'id_nama_tower',
        'detail_lokasi',
        'latitude',
        'longtitude',


    ];

    public function DataTower()
    {
        return $this->belongsTo(DataTower::class, 'id_nama_tower');
    }


}
