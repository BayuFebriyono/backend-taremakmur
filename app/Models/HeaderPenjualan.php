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
}
