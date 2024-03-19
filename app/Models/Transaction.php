<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'barcode',
        'served_by',
        'jumlah_bayar',
        'grandtotal',
        'id_shop'
    ];

 
    public function detail()
    {
        return $this->hasMany(DetailTransaction::class, 'id_transaksi', 'id');
    }
}
