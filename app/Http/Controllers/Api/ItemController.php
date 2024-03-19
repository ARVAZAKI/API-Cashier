<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Item::where('id_shop', '=', Auth::user()->id_shop)->orderBy('nama_barang', 'asc')->get(['id','nama_barang','harga_jual','stock']);
        
            return response()->json([
                'success' => true,
                'message' => 'berhasil mengambil data produk',
                'data' => $data
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk. Error: ' . $e->getMessage(),
            ],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'nama_barang' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stock' => 'required',
            'status' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'gagal menambah kategori',
                'data' => $validator->errors()
            ],401);
        }
            $item = Item::create([
                'barcode' => $request->barcode,
                'id_kategori' => $request->id_kategori,
                'nama_barang' => $request->nama_barang,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stock' => $request->stock,
                'status' => $request->status,
                'tanggal_input' => now()->format('Y-m-d'),                
                'tanggal_kadaluwarsa' => $request->tanggal_kadaluwarsa,
                'input_by' => Auth::user()->name,
                'id_shop' => Auth::user()->id_shop
        ],200);
        $items['nama_barang'] = $item->nama_barang;
        $items['harga_beli'] = $item->harga_beli;
        $items['harga_jual'] = $item->harga_jual;
        $items['stock'] = $item->stock;
        return response()->json([
            'success' => true,
            'message' => 'berhasil menambah data produk',
            'data' => $items
        ],200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       try{
        $item = Item::findOrFail($id)->get();
        return response()->json([
            'success' => true,
            'message' => 'berhasil mendapatkan detail produk',
            'data' => $item
        ]);
       }catch(\Exception $e){
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan',
        ]);
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'nama_barang' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stock' => 'required',
            'status' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'gagal menambah kategori',
                'data' => $validator->errors()
            ],401);
        }
        Item::findOrFail($id)->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'berhasil mengedit data produk',
            'data' => $request->nama_barang
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = Item::find($id);
    
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data produk. Produk dengan ID ' . $id . ' tidak ditemukan.',
                ]);
            }
    
            $item->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data produk',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data produk. Error: ' . $e->getMessage(),
            ]);
        }
    }
    
}
