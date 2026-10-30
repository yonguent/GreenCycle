<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\StoreRequest;
use App\Http\Requests\Api\V1\UpdateRequest;
use App\Http\Resources\TreeResource;
use App\Models\Tree;

class TreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $trees = Tree::query()
        ->latest()
        ->paginate(15);

        return TreeResource::collection($trees);
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
    public function store(StoreRequest $request)
    {
        //

    $tree = $request->user()->trees()->create($request->validated());

    return (new TreeResource($tree))
        ->response()
        ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $tree = Tree::findOrFail($id);
        return (new TreeResource($tree))
            ->response()
            ->setStatusCode(200);
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
    public function update(UpdateRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    $tree = Tree::findOrFail($id);

    $this->authorize('delete', $tree);
    $tree->delete();

    return response()->noContent();
    }   

    public function care(Request $request, Tree $tree)
    {

        $this->authorize('care', $tree);

        if (in_array($tree->status, ['DEAD', 'MATURE']))
            {
                return response()->json(['message' => 'Tree cannot be cared for in its current state'], 409);
            }

        if ($tree->next_care_at && now()->lt($tree->next_care_at)) {
        return response()->json(['message' => 'Aún no puedes cuidar este árbol.',
            'next_care_at' => $tree->next_care_at,
        ], 409);
        }

         $tree->progress += 1;
        $tree->health = min(100, $tree->health + 20);

        if ($tree->progress >= 5) {
        $tree->level += 1;
        $tree->progress = 0;
        }

        if ($tree->level >= 10) {
        $tree->status = 'MATURE';
        }

        $tree->last_cared_at = now();

        $tree->next_care_at = now()->addMinutes(60);
        $tree->save();

        return response()->json($tree, 200);  
    }
}
