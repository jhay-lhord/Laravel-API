<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
        {
            $products = Product::all();

            if($products->isEmpty()){
                return response()->json(['No Products Found'], 404);
            }

            return response()->json([
                'message'=> 'Products Retrieved Successfully',
                'data'=>ProductResource::collection($products)
            ], 200);
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_product = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $product =  Product::create($validated_product);

        return response()->json([
            'message'=>'Product created successfully',
            'product'=> new ProductResource($product)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'message' => "Product:{$id} not found."
            ], 404);
        }

        return response()->json([
            'message' => 'Products retrieved successfully',
            'data'=> new ProductResource($product)
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated_product = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'message'=>"Product: {$id} not found."
            ]);
        }
        $productName = $product->name;
        $product->update($validated_product);

        return response()->json([
            'message'=>"{$productName} updated successfully",
            'product'=> new ProductResource($product)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product =  Product::find($id);
        
        if(!$product){
            return response()->json([
                'message'=>"Product: {$id} not found."], 404);       
        }
        $productName = $product->name;

        $product->destroy($id);
        return response()->json([
            'message'=> "{$productName} deleted successfully"
        ], 200);
    }
}
