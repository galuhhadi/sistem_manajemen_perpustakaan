<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Tampilkan semua buku dengan fitur pencarian.
     * GET /api/books
     */
    public function index(Request $request)
    {
        $books = Book::with('category');

        // Fitur pencarian berdasarkan judul
        if ($request->has('search')) {
            $books->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($books->get(), 200);
    }

    /**
     * Tambah buku baru.
     * POST /api/books
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|digits:4',
            'isbn'             => 'nullable|string|unique:books,isbn',
            'stock'            => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = Book::create($request->all());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $book
        ], 201);
    }

    /**
     * Tampilkan detail satu buku.
     * GET /api/books/{id}
     */
    public function show($id)
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json($book, 200);
    }

    /**
     * Update data buku.
     * PUT /api/books/{id}
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        // Gunakan 'sometimes' agar validasi hanya berjalan jika field dikirim
        $validator = Validator::make($request->all(), [
            'category_id'      => 'sometimes|exists:categories,id',
            'title'            => 'sometimes|string|max:255',
            'author'           => 'sometimes|string|max:255',
            'publisher'        => 'sometimes|string|max:255',
            'publication_year' => 'sometimes|integer|digits:4',
            'isbn'             => 'sometimes|string|unique:books,isbn,' . $id,
            'stock'            => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book->update($request->all());

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => $book
        ], 200);
    }

    /**
     * Hapus buku dari database.
     * DELETE /api/books/{id}
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
}
