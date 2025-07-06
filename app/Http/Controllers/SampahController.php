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
            'harga_per_kg' => 'required|integer',
            'nama_sampah' => 'required|string|max:255'
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
            'nama_sampah' => 'required|string|max:255',
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
        $sampah->nama_sampah = $request->nama_sampah;
        $sampah->save();

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $sampah], 200);
    }

    public function getPenimbanganNasabah(Request $request)
    {

        $id_nasabah = $request->query('id_nasabah');

        $results = Sampah::join('jadwal_penimbangan', 'jadwal_penimbangan.id_jadwal', '=', 'penimbangan.id_jadwal')
            ->join('nasabah', 'nasabah.id_nasabah', '=', 'jadwal_penimbangan.id_nasabah')
            ->join('sampah', 'sampah.id_sampah', '=', 'penimbangan.id_sampah')
            ->select('penimbangan.id_penimbangan', 'penimbangan.tanggal_penimbangan', 'nasabah.nama as nasabah', 'sampah.jenis', 'penimbangan.berat', 'penimbangan.total', 'penimbangan.id_jadwal', 'penimbangan.id_sampah')
            ->where('nasabah.id_nasabah', $id_nasabah)
            ->get();

        return $results;
    }
}
