<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    const styles = ['primary', 'secondary', 'danger', 'warning', 'info', 'dark'];

    public function showNewCategoryForm() {
        return view('categories.create', ['styles' => self::styles]);
    }

    public function storeNewCategory(Request $request)
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

        // error_log("sent");
        $console = new \Symfony\Component\Console\Output\ConsoleOutput();
        $console->writeln("Validated: " . json_encode($validated));

        // return view('posts.index');
    }
}
