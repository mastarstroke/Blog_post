<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PostPageController extends Controller
{
    public function index()
    {
        return view('posts.index');
    }

    public function show($id)
    {
        return view('posts.show', compact('id'));
    }

    public function dashboard()
    {
        return view('posts.dashboard');
    }
}
