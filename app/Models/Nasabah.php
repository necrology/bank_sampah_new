<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    protected $table = 'nasabah';

    protected $fillable = [
        'nama',
        'alamat',
        'nomor_telepon'
    ];

    public $timestamps = false;

    protected $primaryKey = 'id_nasabah';
}