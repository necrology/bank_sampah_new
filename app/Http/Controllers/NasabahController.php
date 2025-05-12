<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NasabahController extends Controller
{
    public function index()
    {

        $results = Nasabah::select(
            'nasabah.*',
            DB::raw("(
            SELECT SUM(total)
            FROM penimbangan
            LEFT JOIN jadwal_penimbangan ON jadwal_penimbangan.id_jadwal = penimbangan.id_jadwal
            WHERE jadwal_penimbangan.id_nasabah = nasabah.id_nasabah
        ) AS saldo")
        )
            ->get();

        return $results;
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

    public function getProfile(Request $request)
    {
        $id_nasabah = $request->query('id_nasabah');

        if (!$id_nasabah) {
            return response()->json(['message' => 'id_nasabah is required'], 400);
        }

        $results = User::join('nasabah', 'nasabah.id_nasabah', '=', 'users.id_nasabah')
            ->where('users.id_nasabah', $id_nasabah)
            ->select(
                'nasabah.*',
                'users.*',
                DB::raw("(
                SELECT SUM(total)
                FROM penimbangan
                LEFT JOIN jadwal_penimbangan ON jadwal_penimbangan.id_jadwal = penimbangan.id_jadwal
                WHERE jadwal_penimbangan.id_nasabah = users.id_nasabah
            ) AS saldo")
            )
            ->first();

        if (!$results) {
            return response()->json(['message' => 'Nasabah not found'], 404);
        }

        return response()->json($results);
    }

    public function dashboard1()
    {

        $results = DB::selectOne("
        SELECT
            (SELECT COUNT(id_nasabah) FROM nasabah) AS nasabah,
            (SELECT SUM(berat) FROM penimbangan 
             LEFT JOIN jadwal_penimbangan ON jadwal_penimbangan.id_jadwal = penimbangan.id_jadwal) AS totalSampah,
            (SELECT SUM(total) FROM penimbangan 
             LEFT JOIN jadwal_penimbangan ON jadwal_penimbangan.id_jadwal = penimbangan.id_jadwal) AS totalSaldo,
            (SELECT COUNT(DISTINCT jenis) FROM sampah) AS kategori
    ");

        return $results;
    }

    public function chart1()
    {

        $results = DB::table('penimbangan')
            ->selectRaw("DATE_FORMAT(tanggal_penimbangan, '%b %Y') as bulan, DATE_FORMAT(tanggal_penimbangan, '%Y-%m') as sort_key, SUM(berat) as total_berat")
            ->groupBy('sort_key', 'bulan')
            ->orderBy('sort_key')
            ->get();

        return $results;
    }

    public function chart2()
    {

        $results = DB::table('penimbangan as p')
            ->join('sampah as s', 's.id_sampah', '=', 'p.id_sampah')
            ->select('s.jenis as kategori', DB::raw('SUM(p.berat) as total_berat'))
            ->groupBy('s.jenis')
            ->orderBy('s.jenis')
            ->get();

        return $results;
    }

    public function leaderboard()
    {

        $results = DB::table('penimbangan as p')
            ->join('jadwal_penimbangan as jp', 'jp.id_jadwal', '=', 'p.id_jadwal')
            ->join('nasabah as n', 'n.id_nasabah', '=', 'jp.id_nasabah')
            ->select('n.id_nasabah', 'n.nama', DB::raw('COUNT(p.id_penimbangan) as jumlah_transaksi'), DB::raw('SUM(p.berat) as total_berat'))
            ->groupBy('n.id_nasabah', 'n.nama')
            ->orderByDesc('total_berat')
            ->limit(10)
            ->get();

        return $results;
    }
}