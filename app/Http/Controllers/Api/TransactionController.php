<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    try {
      $history = Transaction::where('id_shop','=',Auth::user()->id_shop)->with('detail')->get(); 
      $customizedHistory = [];
    foreach ($history as $transaction) {
        $customizedDetail = $transaction->detail->map(function ($detail) {
            return [
                'nama_barang' => $detail->item->nama_barang,
                'jumlah_barang' => $detail->jumlah_barang,
                'subtotal' => $detail->subtotal,
            ];
        });
        $customizedTransaction = [
            'id' => $transaction->id,
            'served_by' => $transaction->served_by,
            'jumlah_bayar' => $transaction->jumlah_bayar,
            'grandtotal' => $transaction->grandtotal,
            'waktu' => Carbon::parse($transaction->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s T'), 
            'detail' => $customizedDetail
        ];
        $customizedHistory[] = $customizedTransaction;
    }
      return response()->json([
        'success' => true,
        'message' => 'berhasil mendapatkan data history transaksi',
        'data' => $customizedHistory,
      ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data history transaksi. Error: ' . $e->getMessage(),
        ], 401);
    }
}

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_barang' => 'required',
            'jumlah_barang' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'gagal menambah barang ke keranjang',
                'data' => $validator->errors()
            ],401);
        }
        $cart = Cart::create([
            'id_barang' => $request->id_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'id_kasir' => Auth::user()->id,
            'id_shop' => Auth::user()->id_shop
        ]);
        $carts['nama_barang'] = $cart->item->nama_barang;
        $carts['jumlah_barang'] = $cart->jumlah_barang;
        return response()->json([
            'success' => true,
            'message' => 'berhasil menambah barang ke keranjang',
            'data' => $carts
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    try {
        $transaction = Transaction::with('detail')->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }

        $customizedDetail = $transaction->detail->map(function ($detail) {
            return [
                'nama_barang' => $detail->item->nama_barang,
                'jumlah_barang' => $detail->jumlah_barang,
                'subtotal' => $detail->subtotal,
            ];
        });

        $customizedTransaction = [
            'id' => $transaction->id,
            'served_by' => $transaction->served_by,
            'jumlah_bayar' => $transaction->jumlah_bayar,
            'grandtotal' => $transaction->grandtotal,
            'waktu' => Carbon::parse($transaction->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s T'),
            'detail' => $customizedDetail
        ];

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data detail transaksi',
            'data' => $customizedTransaction,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data detail transaksi. Error: ' . $e->getMessage(),
        ], 401);
    }
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    // public function checkout(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'jumlah_bayar' => 'required',
    //         'grandtotal' => 'required',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'gagal melakukan transaksi',
    //             'data' => $validator->errors()
    //         ], 401);
    //     }
    
    //     $transaksi = Transaction::create([
    //         'barcode' => $request->barcode,
    //         'served_by' => Auth::user()->name,
    //         'jumlah_bayar' => $request->jumlah_bayar,
    //         'grandtotal' => $request->grandtotal,
    //         'id_shop' => Auth::user()->id_shop,
    //     ]);
    
    //     $latestTransactionId = $transaksi->id;
    
    //     $cartItems = Cart::where('id_kasir', Auth::user()->id)
    //         ->where('id_shop', Auth::user()->id_shop)
    //         ->get();
    
    //     $transactionDetails = [];
    
    //     foreach ($cartItems as $cart) {
    //         $detail = DetailTransaction::create([
    //             'id_transaksi' => $latestTransactionId,
    //             'id_barang' => $cart->id_barang,
    //             'jumlah_barang' => $cart->jumlah_barang,
    //             'subtotal' => $cart->item->harga_jual * $cart->jumlah_barang,
    //             'id_shop' => Auth::user()->id_shop,
    //         ]);
    
    //         $transactionDetails[] = [
    //             'nama_barang' => $cart->item->nama_barang,
    //             'jumlah_barang' => $cart->jumlah_barang,
    //             'jumlah_bayar' => $cart->jumlah_bayar,
    //             'subtotal' => $detail->subtotal,
    //         ];

    //         $item = Item::find($cart->id_barang);
    //         $item->stock -= $cart->jumlah_barang;
    //         $item->save();
    //     }
    
    //     $cartItems->each->delete();
    
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'berhasil melakukan transaksi',
    //         'data' => $transactionDetails,
    //     ], 200);
    // }
    
    public function checkout(Request $request)
{
    $validator = Validator::make($request->all(), [
        'jumlah_bayar' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'gagal melakukan transaksi',
            'data' => $validator->errors()
        ], 401);
    }

    $cartItems = Cart::where('id_kasir', Auth::user()->id)
        ->where('id_shop', Auth::user()->id_shop)
        ->get();

    $grandtotal = $cartItems->sum(function ($cart) {
        return $cart->item->harga_jual * $cart->jumlah_barang;
    });

    $transaksi = Transaction::create([
        'barcode' => $request->barcode,
        'served_by' => Auth::user()->name,
        'jumlah_bayar' => $request->jumlah_bayar,
        'grandtotal' => $grandtotal,
        'id_shop' => Auth::user()->id_shop,
    ]);

    $latestTransactionId = $transaksi->id;

    $transactionDetails = [];

    foreach ($cartItems as $cart) {
        $detail = DetailTransaction::create([
            'id_transaksi' => $latestTransactionId,
            'id_barang' => $cart->id_barang,
            'jumlah_barang' => $cart->jumlah_barang,
            'subtotal' => $cart->item->harga_jual * $cart->jumlah_barang,
            'id_shop' => Auth::user()->id_shop,
        ]);

        $transactionDetails[] = [
            'nama_barang' => $cart->item->nama_barang,
            'jumlah_barang' => $cart->jumlah_barang,
            'jumlah_bayar' => $cart->jumlah_bayar,
            'subtotal' => $detail->subtotal,
        ];

        $item = Item::find($cart->id_barang);
        $item->stock -= $cart->jumlah_barang;
        $item->save();
    }

    $cartItems->each->delete();

    return response()->json([
        'success' => true,
        'message' => 'berhasil melakukan transaksi',
        'data' => $transactionDetails,
        'grandtotal' => $grandtotal, 
    ], 200);
}

}
