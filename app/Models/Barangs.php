<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangs extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];
    public $incrementing = false;
    protected $primaryKey = 'id';
}
