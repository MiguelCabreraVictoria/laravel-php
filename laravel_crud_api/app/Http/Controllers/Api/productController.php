<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products available']);
        }

        $data = [
            'products' => $products,
            'status' => 'success',
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error validating data',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $product = Product::create([
            'name' => $request->name,
            'description'=> $request->description,
            'price'=> $request->price,
            'stock' => $request->stock
        ]);

        if(!$product){
            return response()->json(['message'=> 'Error creating product']);
        } 

        return response()->json($product, 201);
    }

    public function show($id){
        $product = Product::find($id);

        if(!$product){
            return response()->json(['message'=> 'Product not found', 'status'=> 404]);
        }

        $data = [
            'product' => $product,
            'status'=> '200'
        ];

        return response()->json($data, 200);
    }

    public function destroy($id){
        $product = Product::find($id);

        if(!$product){
            return response()->json(['message'=> 'Product not found', 'status'=> 400]);
        };

        $product->delete();

        $data = [
            'message' => 'Product Deleted',
            'status'=> 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $product = Product::find($id);

        if(!$product){
            return response()->json(['message'=> 'Product not found', 'status' => 400]);
        };

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error validating data',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        $product->save();

        $data = [
            'message' => 'Product Updated',
            'status'=> 200
        ];

        return response()->json($data,200);

        
    }
}
