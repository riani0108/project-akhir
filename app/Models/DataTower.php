<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTower extends Model
{
    use HasFactory;
    protected $table = 'data_tower';
    protected $fillable = [
        'nama_tower',
        'alamat_tower',
        'tinggi_tower',
        'latitude',
        'longtitude',
        'keterangan',
    ];

    public function DataAntenna()
    {
        return $this->hasMany(DataAntenna::class, 'id_nama_tower');
    }


}
