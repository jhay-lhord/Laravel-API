<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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
            'data'=>$products
        ]);
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
        $product_created =  Product::create($request->all());

        return response()->json([
            'message'=>'Product created successfully',
            'product'=> $product_created
        ]);
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
            'data'=> $product
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
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'message'=>"Product: {$id} not found."
            ]);
        }
        $productName = $product->name;
        $product->update($request->all());

        return response()->json([
            'message'=>"{$productName} updated successfully",
            'product'=> $product
        ]);
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
        ]);
    }
}
