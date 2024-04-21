<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HeaderPenjualan extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'header_penjualans';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    public $incrementing = false;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function detail_penjualan(){
        return $this->hasMany(DetailPenjualan::class, 'no_invoice', 'no_invoice');
    }

    public function customer(){
        return $this->belongsTo(Customers::class);
    }

}
