<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PostDetailResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        //$posts = Post::with('writer:id,username')->all(); bisa pake method ini bila tak ingin pake load missing samas aja
        // return response()->json(['data'=>$post]);
        return PostDetailResource::collection($posts->loadMissing(['writer:id,username','comments:id,post_id,comments_content,user_id']));
        // $posts = Post::with(['writer:id,username', 'comments'])->get();
        // return PostDetailResource::collection($posts);

    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::with('writer:id,email,username')->findOrFail($id);
        return new PostDetailResource($post->loadMissing(['writer:id,username','comments:id,post_id,comments_content,user_id']));

    }
        /**
     * Display the specified resource.
     */
    public function show2($id)
    {
        $post = Post::FindOrFail($id);
        return new PostDetailResource($post);

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
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);
        $image = null;

        if($request->file)
        {
            //di sini kita upload file
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName.'.'.$extension;
            Storage::putFileAs('image',$request->file, $image);
        }

        $request['image'] =  $image;
        $request['author'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,email,username'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'news_content' => 'required',
            ]);
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,email,username'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return new PostDetailResource($post->loadMissing('writer:id,email,username'));
    }

    function generateRandomString($length = 40) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
