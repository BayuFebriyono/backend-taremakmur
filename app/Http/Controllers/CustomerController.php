<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function all()
    {
        return response()->json(Customers::all());
    }

    public function getById($id){
        return response()->json(Customers::find($id));
    }
}
