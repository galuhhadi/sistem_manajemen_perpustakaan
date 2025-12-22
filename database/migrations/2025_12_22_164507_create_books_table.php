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
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        // Relasi ke Categories (Set Null jika kategori dihapus)
        $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

        $table->string('title');
        $table->string('author');
        $table->string('publisher')->nullable();
        $table->year('publication_year')->nullable();
        $table->string('isbn')->unique()->nullable();
        $table->integer('stock')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
