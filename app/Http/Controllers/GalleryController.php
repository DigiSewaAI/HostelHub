<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(12);
        return view('public.gallery', compact('galleries'));
    }

    public function publicIndex()
    {
        $galleries = Gallery::latest()->paginate(12);
        return view('public.gallery', compact('galleries'));
    }
}
