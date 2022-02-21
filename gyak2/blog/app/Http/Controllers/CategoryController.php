<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showNewCategoryForm() {
        return view('categories.create');
    }

    public function storeNewCategory(Request $request)
    {
        $validated = $request->validate(
            // Validation rules
            [
                'name' => 'required|min:3',
                'style' => 'required',
            ],
            // Custom messages
            [
                'name.required' => 'A kategória nevét kötelező megadni.',
                'name.min' => 'A kategória neve legalább 3 karakter legyen.',
                'required' => 'A(z) :attribute mezőt kötelező kitölteni.'
            ]
        );

        error_log("sent");
        return view('posts.index');
    }
}
