<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function showNewPostForm() {
        return view('posts.create');
    }

    public function storeNewPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:144',
            'description' => 'nullable|max:64',
            'text' => 'required|min:3',
            'cover_image' => 'nullable|file|image|max:2048'
        ]);

        $console = new \Symfony\Component\Console\Output\ConsoleOutput();
        $console->writeln("Validated: " . json_encode($validated));

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');

            $file_hashname = $file->hashName();
            // $console->writeln("Hash name: " . $file_hashname);

            // $file_original_name = $file->getClientOriginalName();
            // $console->writeln("Original name: " . $file_original_name);

            Storage::disk('public')->put('post_images/' . $file_hashname, $file->get());
        }

    }
}
