<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TesteController extends Controller
{
    public function index()
    {
        return view('index',['titulo'=>'meu Titulo']);
    }
}
