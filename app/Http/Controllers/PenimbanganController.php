<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use App\Models\Penimbangan;
use App\Models\Jadwal_penimbangan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenimbanganController extends Controller
{
    public function index()
    {
        $results = Penimbangan::join('jadwal_penimbangan', 'jadwal_penimbangan.id_jadwal', '=', 'penimbangan.id_jadwal')
            ->join('nasabah', 'nasabah.id_nasabah', '=', 'jadwal_penimbangan.id_nasabah')
            ->join('sampah', 'sampah.id_sampah', '=', 'penimbangan.id_sampah')
            ->select('penimbangan.id_penimbangan', 'penimbangan.tanggal_penimbangan', 'nasabah.nama as nasabah', 'sampah.jenis', 'penimbangan.berat', 'penimbangan.total', 'penimbangan.id_jadwal', 'penimbangan.id_sampah', 'sampah.nama_sampah')
            ->get();
        return $results;
    }

    public function fetchJadwal()
    {
        $results = Jadwal_penimbangan::join('nasabah', 'nasabah.id_nasabah', '=', 'jadwal_penimbangan.id_nasabah')
            ->selectRaw("CONCAT (nasabah.nama ,' - ', DATE_FORMAT(jadwal_penimbangan.tanggal, '%d-%m-%Y'), ' - ', jadwal_penimbangan.lokasi) as label, jadwal_penimbangan.tanggal, jadwal_penimbangan.id_jadwal")
            ->where('jadwal_penimbangan.statusPenimbangan', 'F')
            ->get();
        return $results;
    }

    public function fetchSampah()
    {
        $results = Sampah::selectRaw("CONCAT (jenis,' - ', nama_sampah ,' - Rp. ', harga_per_kg, ' /kg') as label, id_sampah, harga_per_kg")
            ->get();
        return $results;
    }

    public function fetchSampahById(Request $request)
    {
        $results = Sampah::select("harga_per_kg")
            ->where('id_sampah', $request->input('id_sampah'))
            ->get();
        return $results;
    }

    public function addDataPenimbangan(Request $request)
    {

        $request->validate([
            'id_jadwal' => 'required|integer|max:255',
            'id_sampah' => 'required|integer|max:255',
            'berat' => 'required|integer|max:255',
            'total' => 'required|integer',
        ]);

        $id_jadwal = $request->input('id_jadwal');

        //UPDATE STATUS JADWAL PENIMBANGAN
        $jadwal_penimbangan = Jadwal_penimbangan::find($id_jadwal);
        $jadwal_penimbangan->statusPenimbangan = 'T';
        $jadwal_penimbangan->save();


        $result = Penimbangan::create($request->all());
        return response()->json($result, 201);
    }

    public function destroy($id)
    {
        $penimbangan = Penimbangan::find($id);

        if ($penimbangan) {
            $penimbangan->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_jadwal' => 'required|integer|max:255',
            'id_sampah' => 'required|integer|max:255',
            'berat' => 'required|integer|max:255',
            'total' => 'required|integer',
        ]);

        // Temukan penimbangan berdasarkan ID
        $penimbangan = Penimbangan::find($id);

        if (!$penimbangan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update data
        $penimbangan->id_jadwal = $request->id_jadwal;
        $penimbangan->id_sampah = $request->id_sampah;
        $penimbangan->berat = $request->berat;
        $penimbangan->total = $request->total;
        $penimbangan->save();

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $penimbangan], 200);
    }

    public function getPenimbanganNasabah(Request $request)
    {

        $id_nasabah = $request->query('id_nasabah');

        $results = Penimbangan::join('jadwal_penimbangan', 'jadwal_penimbangan.id_jadwal', '=', 'penimbangan.id_jadwal')
            ->join('nasabah', 'nasabah.id_nasabah', '=', 'jadwal_penimbangan.id_nasabah')
            ->join('sampah', 'sampah.id_sampah', '=', 'penimbangan.id_sampah')
            ->select('penimbangan.id_penimbangan', 'penimbangan.tanggal_penimbangan', 'nasabah.nama as nasabah', 'sampah.jenis', 'penimbangan.berat', 'penimbangan.total', 'penimbangan.id_jadwal', 'penimbangan.id_sampah', 'sampah.nama_sampah')
            ->where('nasabah.id_nasabah', $id_nasabah)
            ->get();

        return $results;
    }
}