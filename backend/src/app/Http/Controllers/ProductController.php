<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->paginate(10);
    }

    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }
}
