<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Request $request ditambahkan sebagai parameter, untuk membaca query string ?search=...
    $books = Book::when($request->search, function ($query, $search) {
        // Blok ini HANYA dijalankan apabila $request->search tidak kosong
        $query->where('judul', 'like', "%{$search}%")
              ->orWhere('pengarang', 'like', "%{$search}%")
              // Baris baru di bawah ini adalah jawaban Tugas Mandiri 2:
              ->orWhere('kode_buku', 'like', "%{$search}%"); 
    })->latest()->paginate(10)->withQueryString();
    
    // withQueryString() memastikan kata kunci pencarian tetap terbawa saat pindah halaman paginasi
    return inertia('Books/Index', [
        'books' => $books,
        'search' => $request->search, // dikirim balik ke Vue agar kotak pencarian tetap terisi
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia("Books/Create"); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|unique:books|max:255',
            'judul'     => 'required|max:255',
            'pengarang' => 'required|max:255',
            'penerbit'  => 'nullable|max:255',
            'tahun'     => 'nullable|digits:4',
            'stok'      => 'required|integer|min:0', // Diubah menjadi required & min:0
        ]);

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return inertia("Books/Edit", [
            'book' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|unique:books,kode_buku,' . $book->id . '|max:255',
            'judul'     => 'required|max:255',
            'pengarang' => 'required|max:255',
            'penerbit'  => 'nullable|max:255',
            'tahun'     => 'nullable|digits:4',
            'stok'      => 'required|integer|min:0', // Diubah menjadi required & min:0
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.'); 
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
