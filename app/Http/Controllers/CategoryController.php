<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        return CategoryResource::collection(Category::all());
    }

    public function store(CreateCategoryRequest $req) {
        $cat = Category::create($req->validated());
        return new CategoryResource($cat);
    }

    public function show(Category $category) {
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $req, Category $category) {
        $category->update($req->validated());
        return new CategoryResource($category);
    }

    public function destroy(Category $category) {
        $category->delete();
        return response()->noContent();
    }
}
