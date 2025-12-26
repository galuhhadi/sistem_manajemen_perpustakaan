<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Get Buku + Fitur Search (Untuk Katalog Frontend)
    public function index(Request $request) {
        $books = Book::with('category');
        if ($request->has('search')) {
            $books->where('title', 'like', '%' . $request->search . '%');
        }
        return response()->json($books->get());
    }

    // Tambah Buku Baru
    public function store(Request $request) {
        $book = Book::create($request->all());
        return response()->json(['message' => 'Buku berhasil ditambahkan'], 201);
    }
}