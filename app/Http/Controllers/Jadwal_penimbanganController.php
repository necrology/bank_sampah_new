<?php

namespace App\Http\Controllers;

use App\Models\Jadwal_penimbangan;
use App\Models\Jadwal_periode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Jadwal_penimbanganController extends Controller
{
    public function index()
    {
        $results = Jadwal_penimbangan::join('nasabah', 'nasabah.id_nasabah', '=', 'jadwal_penimbangan.id_nasabah')
            ->select('jadwal_penimbangan.id_jadwal', 'jadwal_penimbangan.tanggal', 'jadwal_penimbangan.lokasi', 'nasabah.nama as nasabah', 'nasabah.id_nasabah')
            ->get();
        return $results;
    }

    public function addDataJadwal(Request $request)
    {

        $request->validate([
            'tanggal' => 'required|date',
            'id_nasabah' => 'required|integer|max:255',
            'lokasi' => 'required|string|max:255'
        ]);

        $result = Jadwal_penimbangan::create($request->all());

        return response()->json($result, 201);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal_penimbangan::find($id);

        if ($jadwal) {
            $jadwal->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'id_nasabah' => 'required|integer|max:255',
            'lokasi' => 'required|string|max:255'
        ]);

        // Temukan jadwal berdasarkan ID
        $jadwal = Jadwal_penimbangan::find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update data
        $jadwal->tanggal = $request->tanggal;
        $jadwal->lokasi = $request->lokasi;
        $jadwal->id_nasabah = $request->id_nasabah;
        $jadwal->save();

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $jadwal], 200);
    }

    // Pendaftaran Jadwal Penimbangan
    public function getJadwalPendaftaran(Request $request)
    {

        $id_nasabah = $request->query('id_nasabah');

        $results = Jadwal_penimbangan::select('*')
            ->where('id_nasabah', $id_nasabah)
            ->get();

        return $results;
    }

    public function fetchJadwalPeriode()
    {
        $now = Carbon::now();

        $results = Jadwal_periode::selectRaw("id_periode, tanggal_periode")
            ->whereMonth('tanggal_periode', $now->month)
            ->whereYear('tanggal_periode', $now->year)
            ->get();

        return $results;
    }
}
