<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

use Validator;


class MainController extends Controller
{
    public function userSinglePost(Request $request)
    {
        $post = Post::where([
            ['id',$request->id],
            ['user_id',auth('sanctum')->user()->id]
        ])->get();
        $postCollection = PostResource::collection($post);
        return response()->json([
            'status' => true,
            'message' => 'User Post Details',
            'data'      => $postCollection,
        ], 200);
    }

    public function userPostDelete(Request $request)
    {
        $userPost = Post::where([
            ['id',$request->id],
            ['user_id',auth('sanctum')->user()->id]
        ])->get();
        if ($userPost){
            $post = Post::find($request->id);
            if($post){
                $post->delete();
                $post->save();
                return response()->json([
                    'status' => true,
                    'message' => 'post deleted Successfully',
                ], 200);
            }
        }
    }

    public function userPostsArchive()
    {
        $userPost = Post::where([
            ['user_id',auth('sanctum')->user()->id]
        ])->onlyTrashed()->get();
        $postCollection = PostResource::collection($userPost);
        return response()->json([
            'status' => true,
            'message' => 'User Posts Archive',
            'data'      => $postCollection,
        ], 200);
    }

    public function userPostReactive(Request $request)
    {
        $post = Post::withTrashed()->where('id',$request->id)->first();
        //$post = Post::findOrFail($request->id);
        if ($post)
        {
            $post->restore();
            //$post->save();
            return response()->json([
                'status' => true,
                'message' => 'post reactivated Successfully',
            ], 200);
        }
    }

    public function userPostPinned(Request $request)
    {
        $post = Post::findOrFail($request->id);
        if ($post)
        {
            $post->pinned = 1;
            $post->save();
            return response()->json([
                'status' => true,
                'message' => 'post pinned Successfully',
            ], 200);
        }
    }


    public function forceDelete()
    {
        $post = Post::onlyTrashed()->first();
        if(now()->subDays(30) <= $post->deleted_at){
            $post->forceDelete();
            return response()->json([
                'status' => true,
                'data' =>  'success',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'data' =>  'error',
        ], 200);

    }

    public function countUser()
    {
        $data['users'] = User::get()->count();
        $data['posts'] = Post::get()->count();
        return response()->json([
            'status' => true,
            'data' =>  $data,
        ], 200);
    }
}
