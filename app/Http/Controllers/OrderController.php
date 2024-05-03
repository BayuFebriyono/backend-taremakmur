<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use App\Models\HeaderPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'jenis_pembayaran' => $request->jenis_pembayaran
            /**kredit atau tunai**/
            ,
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


    public function listOrder()
    {
        $header = HeaderPenjualan::where('status', 'WAITING')
            ->with(['user', 'customer', 'detail_penjualan'])
            ->get();
        $header->each(function ($item) {
            $hargaTotal = $item->detail_penjualan->sum('harga');
            unset($item['detail_penjualan']);
            $item['total_harga'] = $hargaTotal;
        });

        return response()->json($header);
    }

    public function generatePdf($no_invoice)
    {
        $data = HeaderPenjualan::where('no_invoice', $no_invoice)
            ->with([
                'detail_penjualan' => function ($query) {
                    $query->with('barang')->orderBy('jenis_barang');
                },
                'user'
            ])
            ->first();

        // Buat PDF
        $pdf = Pdf::loadView('print.nota-penjualan', ['data' => $data])->setPaper([0, 0, 226.772, 600])->output();

        // Simpan PDF ke direktori public/temp_pdf dengan nama yang unik
        $pdfFileName = uniqid('nota_penjualan_') . '.pdf';
        $pdfFilePath = public_path('temp_pdf/' . $pdfFileName);
        file_put_contents($pdfFilePath, $pdf);

        

        // Return link publik ke file PDF
        $publicPdfUrl = url('temp_pdf/' . $pdfFileName);
        return response()->json(['pdf_url' => $publicPdfUrl]);
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
