<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::paginate(10);
        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Publisher::create($request->only('name'));

        return redirect()->route('publishers.index')->with('success', 'Publisher added successfully.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $publisher->update($request->only('name'));

        return redirect()->route('publishers.index')->with('success', 'Publisher updated successfully.');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Publisher deleted successfully.');
    }
}
