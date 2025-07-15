<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class langController extends Controller
{
    public function index($lang){

        if($lang=='ar'){
            Session()->put('lang','ar');
        }else{
            Session()->put('lang','en');
        }
        return redirect()->back();

    }
}
