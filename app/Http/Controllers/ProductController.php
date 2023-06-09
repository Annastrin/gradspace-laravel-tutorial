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
           $this->transformProductImageURL($product);
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

        $path = $request->hasFile('image') ? $this->saveImageFile($request->file('image')) : null;

        $product = Product::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'image' => $path,
        ]);

        return $this->transformProductImageURL($product);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     return Product::find($id);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if ($request->has('title')) {
            $product->title = $request->input('title');
        }

        if ($request->has('description')) {
            $product->description = $request->input('description');
        }

        if ($request->has('price')) {
            $product->price = $request->input('price');
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete($product->image);
            }
            $product->image = $this->saveImageFile($request->file('image'));
        }

        $product->save();

        return $this->transformProductImageURL($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if ($product->image) {
            Storage::delete($product->image);
        }

        return Product::destroy($id);
    }

    private function transformProductImageURL($product) {
        if ($product->image) {
            $product->image = Storage::url($product->image);
        }
        return $product;
    }

    private function saveImageFile($file) {
        $id = uniqid();
        $extension = $file->getClientOriginalExtension();
        return Storage::putFileAs(
            'public/images', $file, $id . '.' . $extension
        );
    }
    /**
     * Search for a name.
     */
    // public function search(string $title)
    // {
    //     return Product::where('title', 'like', '%'.$title.'%')->get();
    // }
}
