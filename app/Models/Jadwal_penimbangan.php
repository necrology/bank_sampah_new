<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal_penimbangan extends Model
{
    protected $table = 'jadwal_penimbangan';

    protected $fillable = [
        'tanggal',
        'id_nasabah',
        'lokasi'
    ];

    public $timestamps = false;

    protected $primaryKey = 'id_jadwal';
}