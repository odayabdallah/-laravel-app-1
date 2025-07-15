<?php


namespace App\Http\Controllers;

use App\Http\Resources\productresource;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class APIProductController extends Controller
{
    public function index()
    {
        $products = product::all();
        $response = productresource::collection($products);

        return response()->json([
            'status' => 'success',
            'data' => $response
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()
            ], 300);
        }

        $image = $request->hasFile('image') ? Storage::putFile('products', $request->image) : null;

        product::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $image,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'status' => 'success'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete($product->image);
            }
            $product->image = Storage::putFile('products', $request->image);
        }

        $product->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $product->image,
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'status' => 'success'
        ], 200);
    }

    public function show($id)
    {
        $product = product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return new productresource($product);
    }

    public function delete($id)
    {
        $product = product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        if ($product->image) {
            Storage::delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
            'status' => 'success'
        ], 200);
    }
}