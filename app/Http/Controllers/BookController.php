<?php

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // Get Buku + Fitur Search (Untuk Katalog Frontend)
    public function index(Request $request) {
        $books = Book::with('category');
    /**
     * TUGAS ANDIN: Menampilkan katalog buku.
     * Dilengkapi fitur Search untuk mencari judul atau penulis.
     * Endpoint: GET /api/books
     */
    public function index(Request $request)
    {
        // Mengambil buku beserta data kategorinya (Eager Loading)
        $query = Book::with('category');

        // Logika Pencarian
        if ($request->has('search')) {
            $books->where('title', 'like', '%' . $request->search . '%');
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
            });
        }
        return response()->json($books->get());

        return response()->json($query->get(), 200);
    }

    // Tambah Buku Baru
    public function store(Request $request) {
    /**
     * TUGAS ANDIN: Menambah buku baru ke database.
     * Endpoint: POST /api/books
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer',
            'isbn'             => 'nullable|string|unique:books,isbn',
            'stock'            => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = Book::create($request->all());
        return response()->json(['message' => 'Buku berhasil ditambahkan'], 201);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $book
        ], 201);
    }

    /**
     * TUGAS ANDIN: Menampilkan detail satu buku.
     * Endpoint: GET /api/books/{id}
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
     * TUGAS ANDIN: Mengupdate data buku atau stok.
     * Endpoint: PUT /api/books/{id}
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'title'       => 'sometimes|required|string|max:255',
            'isbn'        => 'sometimes|required|unique:books,isbn,' . $id,
            'stock'       => 'sometimes|required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book->update($request->all());

        return response()->json([
            'message' => 'Data buku berhasil diperbarui',
            'data'    => $book
        ], 200);
    }

    /**
     * TUGAS ANDIN: Menghapus buku dari sistem.
     * Endpoint: DELETE /api/books/{id}
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
