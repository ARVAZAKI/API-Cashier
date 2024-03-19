<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_barang',
        'jumlah_barang',
        'id_kasir',
        'id_shop'
    ];
    /**
     * Get the item that owns the Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang', 'id');
    }
}
