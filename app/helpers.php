<?php

use App\Models\User;

function getUser($param){
    $user = User::where('id', $param)
                    ->orWhere('username', $param)
                    ->first();


}
