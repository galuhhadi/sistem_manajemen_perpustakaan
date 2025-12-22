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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();

        // Relasi ke Users (Set Null agar log tetap ada walau user dihapus)
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

        $table->string('action'); // CREATE, UPDATE, DELETE, LOGIN
        $table->string('resource'); // Books, Users, dll
        $table->text('description');
        $table->string('ip_address')->nullable();
        $table->text('user_agent')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
