<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        $products->each(function ($product) {
            if ($product->image) {
                $product->image = Storage::url($product->image);
            }
        });

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($request->hasFile('image')) {
            $id = uniqid();
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $path = Storage::putFileAs(
                'public/images', $file, $id . '.' . $extension
            );
        } else {
            $path = null;
        }

        $product = Product::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'image' => $path,
        ]);

        $product->image = Storage::url($path);

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO update image
        $product = Product::find($id);
        $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   // TODO Delete image from storage
        return Product::destroy($id);
    }

    /**
     * Search for a name.
     */
    // public function search(string $title)
    // {
    //     return Product::where('title', 'like', '%'.$title.'%')->get();
    // }
}
