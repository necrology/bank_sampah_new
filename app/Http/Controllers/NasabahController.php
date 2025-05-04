<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NasabahController extends Controller
{
    public function index()
    {
        return Nasabah::all();
    }

    public function addDataNasabah(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:255'
        ]);

        $result = Nasabah::create($request->all());

        return response()->json($result, 201);
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::find($id);

        if ($nasabah) {
            $nasabah->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:255',
        ]);

        // Temukan nasabah berdasarkan ID
        $nasabah = Nasabah::find($id);

        if (!$nasabah) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update data
        $nasabah->nama = $request->nama;
        $nasabah->alamat = $request->alamat;
        $nasabah->nomor_telepon = $request->nomor_telepon;
        $nasabah->save();

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $nasabah], 200);
    }
}