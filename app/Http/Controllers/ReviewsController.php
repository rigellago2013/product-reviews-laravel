<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Reviews;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewsResource;
use Illuminate\Http\Response;

class ReviewsController extends Controller
{
    public function store($id, Request $request)
    {
        $product = Products::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    
        $data = $request->validate([
            'user_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);
    
        $data['product_id'] = $id;
        $review = Reviews::create($data);
    
        return new ReviewsResource($review);
    }
}
