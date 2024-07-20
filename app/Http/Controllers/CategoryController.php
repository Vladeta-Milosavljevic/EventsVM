<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{

    public function index()
    {
        return Inertia::render('Category/CategoryIndex', [Category::get(['id','name'])]);
    }


    public function create()
    {
        //
    }


    public function store(StoreCategoryRequest $request)
    {
        $validatedCategory = $request->validated();
        Category::create($validatedCategory);
        return redirect(route('category.index'));
    }


    public function show(Category $category)
    {
        //
    }


    public function edit(Category $category)
    {
        //
    }


    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedName = $request->validated();
        $category->update($validatedName);
        return redirect(route('category.index'));
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return redirect(route('category.index'));
    }
}
