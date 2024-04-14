<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use App\Models\HeaderPenjualan;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $noInvoice = $this->generateNota();

        // Isi HeaderPenjualan Dulu
        HeaderPenjualan::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $request->customer_id,
            'no_invoice' => $noInvoice,
            'keterangan' => $request->keterangan,
            'jenis_pembayaran' => $request->jenis_pembayaran /**kredit atau tunai**/,
            'uang_muka' => $request->uang_muka,
            'lunas' => $request->lunas,
            'status' => 'WAITING'
        ]);

        $confirmedBarang = collect($request->data);
        $confirmedBarang = $confirmedBarang->map(function ($item) use ($noInvoice) {
            $item['no_invoice'] = $noInvoice;
            return $item;
        });

        $confirmedBarang->each(function ($item) {
            $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
            if ($item['jenis'] == 'dus') {
                $barang->update([
                    'stock_bayangan' => $barang->stock_bayangan - ($item['aktual'] * $barang->jumlah_renteng)
                ]);
            } else {
                $barang->update([
                    'stock_bayangan' => $barang->stock_bayangan - ($item['aktual'])
                ]);
            }
            DetailPenjualan::create($item);
        });

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibuat dengan no invoice ' . $noInvoice
        ]);
    }



    private function generateNota()
    {
        // String awal
        $stringAwal = HeaderPenjualan::select('no_invoice')->latest()->first()->no_invoice ?? 'P0000000';

        // Mengekstrak nomor dari string
        $nomor = intval(substr($stringAwal, 1)); // Mengabaikan huruf 'P'

        // Menambahkan 1 ke nomor
        $nomorBaru = $nomor + 1;

        // Memformat nomor baru dengan 7 digit
        $nomorFormat = sprintf('%07d', $nomorBaru);

        // Menyusun kembali string dengan huruf 'P' dan nomor yang diformat
        $stringBaru = "P" . $nomorFormat;
        return $stringBaru;
    }
}
