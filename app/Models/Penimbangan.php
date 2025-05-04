<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penimbangan extends Model
{
    protected $table = 'penimbangan';

    protected $fillable = [
        'berat',
        'id_jadwal',
        'id_sampah',
        'total',
    ];

    public $timestamps = false;

    protected $primaryKey = 'id_penimbangan';
}