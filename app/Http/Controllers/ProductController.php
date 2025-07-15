<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.product.index')->with('products', $products);
    }

    public function create()
    {
        return view('admin.product.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = Storage::putFile('products', $request->file('image'));
        }

        Product::create($data);

        session()->flash('success', 'Product created successfully');
        return redirect()->route('products'); // تأكد من وجود اسم المسار
    }
    public function edit($id){
        $product=Product::findOrfail($id);
        return view('admin.product.edit',compact('product'));
    }

    public function update( Request $request,$id){
        $product=Product::findOrfail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $data['image'] = Storage::putFile('products', $request->file('image'));
        }
        $product->update($data);
        session()->flash('success', 'Product updated  successfully');
        return redirect()->route('products');
  

    }
    public function delete($id){
        $product=Product::findOrfail($id);
        if($product->image){
         Storage::delete($product->image);
        }
        $product->delete();
        session()->flash('success', 'Product deleted  successfully');
        return redirect()->route('products');
  


    }
}