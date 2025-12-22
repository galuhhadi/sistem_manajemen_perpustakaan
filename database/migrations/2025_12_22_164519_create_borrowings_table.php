<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('borrowings', function (Blueprint $table) {
        $table->id();

        // Relasi ke Users & Books (Cascade: Hapus peminjaman jika user/buku dihapus)
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();

        $table->date('borrow_date');
        $table->date('due_date'); // Batas waktu kembali
        $table->date('return_date')->nullable(); // Diisi saat dikembalikan
        $table->enum('status', ['borrowed', 'returned'])->default('borrowed');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
