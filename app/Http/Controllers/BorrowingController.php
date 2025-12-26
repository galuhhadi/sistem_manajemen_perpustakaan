<?php

namespace App\Http\Controllers;

// WAJIB: Import semua model yang digunakan agar tidak error "Class not found"
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Path: app/Http/Controllers/BorrowingController.php
     * Tugas: Sirkulasi & Logic Denda
     */

    // Pinjam Buku (Logic Stok)
    public function borrow(Request $request) {
        $book = Book::findOrFail($request->book_id);

        // Validasi ketersediaan stok
        if ($book->stock <= 0) {
            return response()->json(['message' => 'Stok buku sedang habis!'], 400);
        }

        $borrow = Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'borrow_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(7), // Default pinjam 7 hari
            'status' => 'borrowed'
        ]);

        // Kurangi stok buku secara otomatis
        $book->decrement('stock');

        // GIT: git add . && git commit -m "Deyaa: Menambahkan fungsi pinjam dan decrement stok"
        return response()->json($borrow, 201);
    }

    // Kembali Buku (Logic Denda)
    public function returnBook($id) {
        $borrow = Borrowing::findOrFail($id);

        // Update status peminjaman
        $borrow->update([
            'return_date' => Carbon::now(),
            'status' => 'returned'
        ]);

        // Kembalikan stok buku
        Book::find($borrow->book_id)->increment('stock');

        // Algoritma Kalkulasi Denda Keterlambatan
        $due = Carbon::parse($borrow->due_date);
        $now = Carbon::now();

        if ($now->gt($due)) {
            $days = $now->diffInDays($due);
            Fine::create([
                'borrowing_id' => $borrow->id,
                'amount' => $days * 2000, // Tarif denda: Rp 2.000 / hari
                'status' => 'unpaid'
            ]);
        }

        // GIT: git add . && git commit -m "Deyaa: Logic pengembalian dan kalkulasi denda otomatis"
        return response()->json(['message' => 'Buku berhasil dikembalikan']);
    }
}
