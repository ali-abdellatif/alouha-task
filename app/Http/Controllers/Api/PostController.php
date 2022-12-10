<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('user_id',auth('sanctum')->user()->id)->get();
        $postCollection = PostResource::collection($posts);
        return response()->json([
            'status' => true,
            'message' => 'User Posts',
            'data'      => $postCollection,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //$user = auth('sanctum')->user()->id;

            $validateUser = Validator::make($request->all(),
                [
                    'title'      => 'required',
                    'body'       => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if ($request->has('image')){
                $file = uploadImage('posts',$request->image);
                //$filePath = uploadImage('posts', $request->image);
            }
            $post = Post::create([
                'user_id'           => auth('sanctum')->user()->id,
                'title'             => $request->title,
                'body'              => $request->body,
                'image'             => $file,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data'      => $post,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if($post){
            $post->is_active = false;
            return response()->json([
                'status' => true,
                'message' => 'post deleted Successfully',
            ], 200);
        }
    }
}
