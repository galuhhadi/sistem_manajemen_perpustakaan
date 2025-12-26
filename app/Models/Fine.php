<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     * * @var array
     */
    protected $fillable = [
        'borrowing_id',
        'amount',
        'status',
        'paid_at'
    ];

    /**
     * Relasi: Satu Denda dimiliki oleh satu Peminjaman.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
