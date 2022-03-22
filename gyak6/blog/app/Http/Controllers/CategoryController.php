<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    const styles = ['primary', 'secondary', 'danger', 'warning', 'info', 'dark'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create', ['styles' => self::styles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3',
                'style' => 'required|in:'.join(",", self::styles),
            ],
            // Custom messages
            [
                'name.required' => 'A kategória nevét kötelező megadni.',
                'name.min' => 'A kategória neve legalább :min karakter legyen.',
                'required' => 'A(z) :attribute mezőt kötelező kitölteni.'
            ]
        );

        $category = Category::create($validated);

        $request->session()->flash('category-created', $category->name);
        return redirect()->route('categories.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', ['styles' => self::styles, 'category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $old_category_name = $category->name;
        $old_category_style = $category->style;

        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3',
                'style' => 'required|in:'.join(",", self::styles),
            ],
            // Custom messages
            [
                'name.required' => 'A kategória nevét kötelező megadni.',
                'name.min' => 'A kategória neve legalább :min karakter legyen.',
                'required' => 'A(z) :attribute mezőt kötelező kitölteni.'
            ]
        );

        $category->update($validated);

        $request->session()->flash('category-updated', array($old_category_style, $old_category_name, $category->style, $category->name));
        return redirect()->route('categories.edit', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
