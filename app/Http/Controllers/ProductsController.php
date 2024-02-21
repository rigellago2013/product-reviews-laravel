<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    //Get all products
    public function index()
    {
        $products = Products::with('reviews')->get();
        return ProductsResource::collection($products);
    }

    //Show product by id
    public function show($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new ProductsResource($product);
    }

    //Store product
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $product = Products::create($data);
        return new ProductsResource($product);
    }

    //Update product
    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);
    
        $product->update($data);
        return new ProductsResource($product);
    }

    //Delete product
    public function destroy($id)
    {
        $product = Products::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
    
}
