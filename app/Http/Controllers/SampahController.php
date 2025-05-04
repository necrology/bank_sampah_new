<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sampah;

class SampahController extends Controller
{
    public function index()
    {
        return Sampah::all();
    }

    public function addDataSampah(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'harga_per_kg' => 'required|integer'
        ]);

        $result = Sampah::create($request->all());

        return response()->json($result, 201);
    }

    public function destroy($id)
    {
        $sampah = Sampah::find($id);

        if ($sampah) {
            $sampah->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jenis' => 'required|string|max:255',
            'harga_per_kg' => 'required|numeric',
        ]);

        // Temukan sampah berdasarkan ID
        $sampah = Sampah::find($id);

        if (!$sampah) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update data
        $sampah->jenis = $request->jenis;
        $sampah->harga_per_kg = $request->harga_per_kg;
        $sampah->save();

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $sampah], 200);
    }
}
