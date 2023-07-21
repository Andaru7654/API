<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required',


        ]);

        $request ['user_id'] = auth()->user()->id;
         $comment = Comment::create($request->all());
        //  return response()->json($comment->loadmissing(['comentator']));

        return new CommentResource($comment->loadMissing(['comentator:id,username']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'comments_content' => 'required',
        ]);

        $comment = Comment::findOrFail($id); // Mengambil data comment berdasarkan ID
        $comment->update($request->only('comments_content'));

        return new CommentResource($comment->loadMissing(['comentator:id,username']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {


        $comment = Comment::findOrFail($id); // Mengambil data comment berdasarkan ID
        $comment->delete();

        return new CommentResource($comment->loadMissing(['comentator:id,username']));
    }
}
