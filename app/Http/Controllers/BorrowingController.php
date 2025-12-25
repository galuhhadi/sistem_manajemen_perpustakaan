<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    // Pinjam Buku (Logic Stok)
    public function borrow(Request $request) {
        $book = Book::findOrFail($request->book_id);
        if ($book->stock <= 0) return response()->json(['msg' => 'Stok Habis'], 400);

        $borrow = Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'borrow_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(7),
            'status' => 'borrowed'
        ]);
        $book->decrement('stock');
        return response()->json($borrow, 201);
    }

    // Kembali Buku (Logic Denda)
    public function returnBook($id) {
        $borrow = Borrowing::findOrFail($id);
        $borrow->update(['return_date' => Carbon::now(), 'status' => 'returned']);
        Book::find($borrow->book_id)->increment('stock');

        // Algoritma Denda Keterlambatan
        $due = Carbon::parse($borrow->due_date);
        if (Carbon::now()->gt($due)) {
            $days = Carbon::now()->diffInDays($due);
            Fine::create([
                'borrowing_id' => $borrow->id,
                'amount' => $days * 2000, // Rp 2.000 / hari
                'status' => 'unpaid'
            ]);
        }
        return response()->json(['message' => 'Buku dikembalikan']);
    }
}