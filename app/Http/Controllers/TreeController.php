<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tree;

class TreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $trees = Tree::all();
        return view('index', compact('trees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
     $validated = $request->validate([
        'name'    => 'required|string|max:255',
        'user_id' => 'required|integer|exists:users,id',
    ]);

    $tree = Tree::create([
        'name'    => $validated['name'],
        'user_id' => $validated['user_id'],
    ]);

    return response()->json($tree, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $tree = Tree::findOrFail($id);
        return view('show', compact('tree'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
