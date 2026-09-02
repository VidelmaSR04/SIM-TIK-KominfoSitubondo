<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerPhotoController extends Controller
{
    public function index()
    {
        return view('server-foto.index'); // Assuming view exists
    }
}