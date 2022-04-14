<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;

use Auth;

class CategoryController extends Controller
{
    const styles = ['primary', 'secondary', 'succes', 'danger', 'warning', 'info', 'dark'];

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
        if (!Auth::user()->can('create', Category::class)) abort(403);

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
        if (!Auth::user()->can('create', Category::class)) abort(403);

        $validated = $request->validate(
            [
                'name' => 'required|min:3|max:20',
                'style' => 'required|in:' . join(',', self::styles)
            ]
        );

        Category::create($validated);

        $request->session()->flash('category-created', $validated['name']);

        return redirect()->route('categories.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('categories.show', [
            'category' => $category,
            'users_count' => Users::count(),
            'categories' => Category::all(),
            'total_posts' => Post::count(),
            'posts' => $category->posts()->with('categories')->paginate(9)

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        if (!Auth::user()->can('update', $category)) abort(403);

        return view('categories.edit', [
            'styles' => self::styles,
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if (!Auth::user()->can('update', $category)) abort(403);

        $validated = $request->validate(
            [
                'name' => 'required|min:3|max:20',
                'style' => 'required|in:' . join(',', self::styles)
            ]
        );

        $category->update($validated);

        $request->session()->flash('category-updated', $validated['name']);

        return redirect()->route('categories.edit', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (!Auth::user()->can('delete', $category)) abort(403);

        $deleted = $category->delete();
        if(!$deleted) return abort(500);

        $request->session()->flash('category-deleted', $category->name);

        return redirect()->route('posts.index');
    }
}
