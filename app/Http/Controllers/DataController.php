<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function session()
    {
        return response()->json(['data' => session()->all()]);
    }
}
