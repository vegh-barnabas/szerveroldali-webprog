<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;

use Storage;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index', [
            'users_count' => User::count(),
            'categories' => Category::all(),
            'posts' => Post::with('categories')->withTrashed()->paginate(9)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('create', Post::class)) abort(403);

        $validated = $request->validate(
            [
                'title' => 'required|min:3|max:144',
                'description' => 'nullable|max:64',
                'text' => 'required|min:3',
                'categories' => 'nullable',
                'categories.*' => 'integer|distinct|exists:categories,id',
                'post_hidden' => 'nullable|boolean',
                'cover_image' => 'nullable|file|image|max:2048',
            ],
        );

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $validated['cover_image_filename'] = $file->getClientOriginalName();
            $validated['cover_image_hashname'] = $file->hashName();
            Storage::disk('public')->put('covers/' . $validated['cover_image_hashname'], $file->get());
        }

        $validated["author_id"] = Auth::id();

        $post = Post::create($validated);

        // Attach categories
        $post->categories()->attach($request->categories);

        $request->session()->flash('post-created', $post->title);
        return redirect()->route('posts.show', $post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'default_cover' => $post->cover_image_hashname
                ? 'storage/covers/' . $post->cover_image_hashname
                : 'images/default_post_cover.jpg'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('update', $post)) abort(403);

        return view('posts.edit', [
            'categories' => Category::all(),
            'post' => $post,
            'default_cover' => $post->cover_image_hashname
                ? 'storage/covers/' . $post->cover_image_hashname
                : 'images/default_post_cover.jpg'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (!Auth::user()->can('update', $post)) abort(403);

        $validated = $request->validate(
            [
                'title' => 'required|min:3|max:144',
                'description' => 'nullable|max:64',
                'text' => 'required|min:3',
                'categories' => 'nullable',
                'categories.*' => 'integer|distinct|exists:categories,id',
                'post_hidden' => 'nullable|boolean',
                'cover_image' => 'nullable|file|image|max:2048',
            ],
        );

        if ($request->has('remove_cover_image')) {
            if ($post->cover_image_hashname) {
                Storage::disk('public')->delete('covers/' . $post->cover_image_hashname);
            }
        } else {
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $validated['cover_image_filename'] = $file->getClientOriginalName();
                $validated['cover_image_hashname'] = $file->hashName();
                Storage::disk('public')->put('covers/' . $validated['cover_image_hashname'], $file->get());
            }
        }

        $post->update($validated);

        // Sync categories
        $post->categories()->sync($request->categories);

        $request->session()->flash('post-updated', $post->title);
        return redirect()->route('posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        if (!Auth::user()->can('delete', $post)) abort(403);

        $deleted = $post->delete();
        if (!$deleted) return abort(500);

        $request->session()->flash('post-deleted', $post->title);
        return redirect()->route('posts.index');
    }

    /**
     * Restore the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id) {
        $post = Post::withTrashed()->find($id);
        if (!$post) abort(404);

        if (!Auth::user()->can('restore', $post)) abort(403);

        $restored = $post->restore();
        if (!$restored) return abort(500);

        $request->session()->flash('post-restored', $post->title);
        return redirect()->route('posts.index');
    }
}
