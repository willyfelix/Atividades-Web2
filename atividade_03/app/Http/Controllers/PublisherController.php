<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;

class PublisherController extends Controller
{

    public function index()
    {
        $publishers = Publisher::all();
        return view('publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:authors|max:255',
        ]);
        Publisher::create($request->all());
        return redirect()->route('publishers.index')->with('success', 'Editora criada com sucesso.');
    }

    public function show(string $id)
    {
        return view('publishers.show', compact('publisher'));
    }

    public function edit(string $id)
    {
        return view('publishers.edit', compact('publisher'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:authors,name,' . $id . '|max:255',
        ]);

        $publisher->update($request->all());

        return redirect()->route('publishers.index')->with('success', 'Editora atualizada com sucesso.');
    }


    public function destroy(string $id)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Editora excluída com sucesso.');
    }
}
