<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'data'=> $categories,
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'message'=> 'Category not found',
            ], 404);
        }

        return response()->json([
            'data'=> $category,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:25',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message'=> 'Category created successfully',
            'data'=> $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'message'=> 'Category not found',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|min:3|max:25',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message'=> 'Category updated successfully',
            'data'=> $category,
        ]);
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'message'=> 'Category not found',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message'=> 'Category deleted successfully',
        ]);
    }

}
