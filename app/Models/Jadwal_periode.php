<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal_periode extends Model
{
    protected $table = 'jadwal_periode';
    protected $fillable = [
        'tanggal_periode',
    ];

    public $timestamps = false;

    protected $primaryKey = 'id_periode';
}