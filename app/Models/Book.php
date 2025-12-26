<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (Fillable)
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'stock'
    ];

    /**
     * Relasi: Satu Buku punya satu Kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Satu Buku bisa dipinjam berkali-kali
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
