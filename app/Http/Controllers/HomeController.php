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

    public function getProductList(Request $request){

        $sort = $request->sort;
        $sortBy = [];

        switch($sort){
            case 0:
                $sortBy['field'] = 'id';
                $sortBy['sortBy'] = 'desc';
                break;
            case 1:
                $sortBy['field'] = 'price';
                $sortBy['sortBy'] = 'asc';
                break;
            case 2:
                $sortBy['field'] = 'price';
                $sortBy['sortBy'] = 'desc';
                break;
            default:
                $sortBy['field'] = 'id';
                $sortBy['sortBy'] = 'desc';
        }

        $min = $request->min ?? null;
        $max = $request->max ?? null;

        $category = $request->category ?? null;

        $products = Product::where('id','>',0);

        if(!is_null($min) && !is_null($max)){
            $products = Product::whereBetween('price', [$min, $max]); // table 100 -> 27
        }

        if(!is_null($category) && $category != 'all'){
            $products = $products->where('category_id', $category);
        }

        $products = $products->orderBy($sortBy['field'],$sortBy['sortBy'])->paginate(6);

        $productCategories = ProductCategory::orderBy('name', 'desc')->get()
        ->filter(function ($productCategory) {
            return ($productCategory->getProducts->count() > 0);
        })->values();

        return view('frontend.product_list',[
            'products' => $products,
            'min' => Product::min('price'),
            'max' => Product::max('price'),
            'productCategories' => $productCategories
        ]);
    }
}
