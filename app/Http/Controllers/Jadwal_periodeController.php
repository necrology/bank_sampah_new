<?php

namespace App\Http\Controllers;

use App\Models\Jadwal_periode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Jadwal_periodeController extends Controller
{
    public function index()
    {
        return Jadwal_periode::all();
    }

    public function addDataJadwalPeriode(Request $request)
    {

        $request->validate([
            'tanggal_periode' => 'required|date',
        ]);

        $result = Jadwal_periode::create($request->all());

        return response()->json($result, 201);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal_periode::find($id);

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
            'tanggal_periode' => 'required|date',
        ]);

        // Temukan jadwal berdasarkan ID
        $jadwal = Jadwal_periode::find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Update data
        $jadwal->tanggal_periode = $request->tanggal_periode;
        $jadwal->save();

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $jadwal], 200);
    }
}
