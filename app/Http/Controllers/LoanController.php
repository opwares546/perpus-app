<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['member', 'book'])
            ->orderByDesc('id')
            ->paginate(10);

        return inertia('Loans/Index', [
            'loans' => $loans,
        ]);
    }

    public function create()
    {
        $members = Member::orderBy('nama')->get(['id', 'nama', 'nis_nim']);
        $books = Book::where('stok', '>', 0)
            ->orderBy('judul')
            ->get(['id', 'judul', 'stok']);

        return inertia('Loans/Create', [
            'members' => $members,
            'books' => $books,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id'   => 'required|exists:books,id',
        ], [
            'member_id.required' => 'Anggota wajib dipilih.',
            'member_id.exists'   => 'Anggota yang dipilih tidak ditemukan.',
            'book_id.required'   => 'Buku wajib dipilih.',
            'book_id.exists'     => 'Buku yang dipilih tidak ditemukan.',
        ]);

        // Tugas Mandiri 3: Batasi maksimal 3 buku sedang dipinjam per anggota
        $sedangDipinjam = Loan::where('member_id', $validated['member_id'])
            ->where('status', 'dipinjam')
            ->count();

        if ($sedangDipinjam >= 3) {
            return back()->withErrors([
                'member_id' => 'Anggota ini sudah mencapai batas maksimal 3 buku yang sedang dipinjam.',
            ]);
        }

        // Validasi stok di server
        $book = Book::findOrFail($validated['book_id']);
        if ($book->stok < 1) {
            return back()->withErrors([
                'book_id' => 'Stok buku ini sudah habis. Pilih buku lain.',
            ]);
        }

        // Simpan peminjaman dan kurangi stok sebagai satu kesatuan
        DB::transaction(function () use ($validated, $book) {
            Loan::create([
                'member_id'               => $validated['member_id'],
                'book_id'                 => $validated['book_id'],
                'tanggal_pinjam'          => now()->toDateString(),
                'tanggal_kembali_rencana' => now()->addDays(14)->toDateString(), // Tugas Mandiri 1: 14 hari
                'status'                  => 'dipinjam',
            ]);

            $book->decrement('stok');
        });

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil disimpan. Stok buku berkurang 1.');
    }
    public function kembalikan(Loan $loan)
    {
        if ($loan->status === 'dikembalikan') {
            return back()->withErrors([
                'loan' => 'Buku ini sudah dikembalikan sebelumnya.',
            ]);
        }

        $hariTerlambat = $loan->hari_terlambat;
        $denda = $loan->perkiraan_denda;

        DB::transaction(function () use ($loan, $denda) {
            $loan->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => today()->toDateString(),
                'denda' => $denda,
            ]);

            $loan->book->increment('stok');
        });

        $pesan = 'Buku berhasil dikembalikan. Stok buku bertambah 1.';
        if ($denda > 0) {
            $pesan .= ' Terlambat ' . $hariTerlambat . ' hari, denda Rp' . number_format($denda, 0, ',', '.') . '.';
        }

        return back()->with('success', $pesan);
    }
}