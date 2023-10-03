<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $requests)
    {
        return response()->json('Hello world');
    }
}
