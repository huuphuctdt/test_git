<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getProduct(){
// Auth::logout();
        //Eloquent
        $products = Product::orderBy('id','desc')->limit(12)->get();

        $productCategories = ProductCategory::orderBy('name', 'desc')->get();

        // dd($productCategories);

        $productCategories = ProductCategory::orderBy('name', 'desc')->get()->filter(function ($productCategory) {
            return ($productCategory->getProducts->count() > 0);
        })->values();

        return view('frontend.home')
        ->with('productCategories', $productCategories)
        ->with('products', $products);
    }
}
