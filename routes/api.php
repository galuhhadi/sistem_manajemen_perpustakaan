<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Error "Auth guard [api] is not defined" biasanya berarti kamu perlu
| mengecek file config/auth.php dan memastikan guard 'api' sudah
| menggunakan driver 'jwt'.
|
*/

// --- 1. PUBLIC ROUTES (Bisa diakses tanpa login) ---
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('books', [BookController::class, 'index']); // Katalog buku untuk umum

// --- 2. PROTECTED ROUTES (Wajib Login JWT & Log Aktivitas Otomatis) ---
// Gunakan 'auth:api' hanya jika sudah dikonfigurasi di config/auth.php
Route::middleware(['auth:api', \App\Http\Middleware\LogActivity::class])->group(function () {

    // Profile User (Tugas Galuh)
    Route::get('me', [AuthController::class, 'me']);

    // Manajemen Kategori (Tugas Andin)
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    // Manajemen Buku Tambahan (Tugas Andin)
    Route::post('books', [BookController::class, 'store']);
    Route::get('books/{id}', [BookController::class, 'show']);
    Route::put('books/{id}', [BookController::class, 'update']);
    Route::delete('books/{id}', [BookController::class, 'destroy']);

    // Sirkulasi & Denda (Tugas Deyaa)
    Route::post('borrow', [BorrowingController::class, 'borrow']);
    Route::put('borrow/{id}/return', [BorrowingController::class, 'returnBook']);

    // Melihat daftar denda
    Route::get('fines', function() {
        return \App\Models\Fine::with('borrowing')->get();
    });

    // Melihat Log Aktivitas (Opsional untuk Admin)
    Route::get('logs', function() {
        return \App\Models\ActivityLog::with('user')->latest()->get();
    });

});
    // public function update(Request $request, $id)
    // {
    //     $category = Category::find($id);
    //     if (!$category) {
    //         return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'sometimes|required|string|max:100',
    //         'description' => 'nullable|string'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     $category->update($request->only(['name', 'description']));

    //     return response()->json([
    //         'message' => 'Kategori berhasil diperbarui',
    //         'data' => $category
    //     ]);
    // }
