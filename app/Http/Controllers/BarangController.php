<?php

namespace App\Http\Controllers;

use App\Models\Barangs;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function all()
    {
        return response()->json([
            'status' => true,
            'data' => Barangs::all()
        ]);
    }

    public function searchBarang($kode)
    {
        return response()->json([
            'status' => true,
            'data' => Barangs::where('kode_barang', 'like', '%' . $kode . '%')->get()
        ]);
    }

    public function getById($id)
    {
        return response()->json([
            'status' => true,
            'data' => Barangs::find($id)
        ]);
    }
}
