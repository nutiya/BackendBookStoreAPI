<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::paginate(10);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Language::create($request->only('name'));

        return redirect()->route('languages.index')->with('success', 'Language added successfully.');
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $language->update($request->only('name'));

        return redirect()->route('languages.index')->with('success', 'Language updated successfully.');
    }

    public function destroy(Language $language)
    {
        $language->delete();

        return redirect()->route('languages.index')->with('success', 'Language deleted successfully.');
    }
}
