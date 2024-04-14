<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory,HasUuids;
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $table = 'detail_penjualans';
}
