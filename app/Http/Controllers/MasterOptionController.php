<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterOptionController extends Controller
{
    public function index()
    {
        return view('server-master.index'); // Assuming view exists
    }
}