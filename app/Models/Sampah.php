<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    protected $table = 'sampah';

    protected $fillable = [
        'jenis',
        'harga_per_kg'
    ];

    public $timestamps = false;

    protected $primaryKey = 'id_sampah';
}
