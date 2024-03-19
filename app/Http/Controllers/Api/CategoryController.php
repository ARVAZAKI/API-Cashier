<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Toko;
use App\Models\User;
use App\Models\Category;
use App\Models\Kategori;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Category::where('id_shop', '=', Auth::user()->id_shop)->orderBy('nama_kategori', 'asc')->get(['id','nama_kategori']);
        
            return response()->json([
                'success' => true,
                'message' => 'berhasil mengambil data kategori',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kategori. Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'nama_kategori' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'gagal menambah kategori',
                    'data' => $validator->errors()
                ],401);
            }
            $category = Category::create([
            'nama_kategori' => $request->nama_kategori,
            'id_shop' => Auth::user()->id_shop
        ]);
        return response()->json([
            'success' => true,
            'message' => 'berhasil menambah data kategori',
            'data' => $request->nama_kategori
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            $data = Item::where('id_shop', '=', Auth::user()->id_shop)->where('id_kategori', '=', $id)->orderBy('nama_barang', 'asc')->get([
                'nama_barang',
                'harga_beli',
                'harga_jual',
                'stock',
                'status',
                'tanggal_input',
                'tanggal_kadaluwarsa',
            ]);
                return response()->json([
                'success' => true,
                'message' => 'berhasil mengambil data produk',
                'data' => $data
            ]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'gagal menambah kategori',
                'data' => $validator->errors()
            ],401);
        }
            Category::findOrFail($id)->update([
                'nama_kategori' => $request->nama_kategori,
                'id_shop' => Auth::user()->id_shop
            ]);
            return response()->json([
                'success' => true,
                'message' => 'berhasil mengedit data kategori',
                'data' => $request->nama_kategori
            ]);
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kategori = Category::find($id);
    
            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data kategori. kategori dengan ID ' . $id . ' tidak ditemukan.',
                ],401);
            }
    
            $kategori->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data kategori',
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data kategori. Error: ' . $e->getMessage(),
            ],401);
        }
    }
    
}
