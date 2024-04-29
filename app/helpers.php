<?php

use App\Models\User;

function getUser($param){
    $user = User::where('id', $param)
                    ->orWhere('username', $param)
                    ->first();


}

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        $rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $rupiah;
    }
}

if (!function_exists('formatAngka')) {
    function formatAngka($angka)
    {
        $rupiah = number_format($angka, 0, ',', '.');
        return $rupiah;
    }
}
