<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku      = Book::count();
        $totalStok      = (int) Book::sum('stok');
        $totalAnggota   = Member::count();
        $sedangDipinjam = Loan::where('status', 'dipinjam')->count();
        $terlambat      = Loan::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', today()->toDateString())
            ->count();
        $totalDenda     = (int) Loan::sum('denda');
        
        // Tugas Mandiri J.2: Peminjaman Hari Ini
        $pinjamHariIni  = Loan::where('tanggal_pinjam', today()->toDateString())->count();

        // Tugas Mandiri J.3: Grafik 12 bulan terakhir ($i = 11 turun ke 0)
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                      'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $label = [];
        $jumlah = [];

        for ($i = 11; $i >= 0; $i--) {
            $awal  = today()->startOfMonth()->subMonths($i);
            $akhir = $awal->copy()->endOfMonth();

            $label[]  = $namaBulan[$awal->month - 1] . ' ' . $awal->year;
            $jumlah[] = Loan::whereBetween('tanggal_pinjam', [
                $awal->toDateString(),
                $akhir->toDateString(),
            ])->count();
        }

        return inertia('Dashboard', [
            'statistik' => [
                'total_buku'      => $totalBuku,
                'total_stok'      => $totalStok,
                'total_anggota'   => $totalAnggota,
                'sedang_dipinjam' => $sedangDipinjam,
                'terlambat'       => $terlambat,
                'total_denda'     => $totalDenda,
                'pinjam_hari_ini' => $pinjamHariIni,
            ],
            'grafik' => [
                'label'  => $label,
                'jumlah' => $jumlah,
            ],
        ]);
    }
}