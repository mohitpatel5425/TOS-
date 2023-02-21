<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->guard('api')->user()->role == 1){
                
            $users = Post::where('user_id',auth()->guard('api')->user()->id)->get();
            return response(['users'=>PostResource::collection($users),'message'=>'Successful','status'=>'200']);

        }else{

            $users = Post::all();
            return response([ 'users' => 
            PostResource::collection($users), 
            'message' => 'Successful','status'=>'200']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|max:150',
            'post' => 'required',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 
            'Validation Error']);
        }

        $data['user_id'] = auth()->guard('api')->user()->id;
        $post = Post::create($data);

        return response([ 'post' => new 
        PostResource($post), 
        'message' => 'Post created Successfully.','status'=>'200']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {   
        if(auth()->guard('api')->user()->role == 1){
            if($post->user_id == auth()->guard('api')->user()->id){

                return response([ 'post' => new PostResource($post), 'comments' => $post->comments, 'message' => 'Post Details Fetched Successfully','status'=>'200']);
            }else{
                return response([ 'message' => 'You do not have rights to see this post','status'=>'400']);
            }

        }else{

            return response([ 'post' => new PostResource($post), 'comments' => $post->comments, 'message' => 'Post Details Fetched Successfully','status'=>'200']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if(auth()->guard('api')->user()->role == 1){
            if($post->user_id == auth()->guard('api')->user()->id){
                
                $data = $request->validate([
                    'title' => 'required|max:150',
                    'post' => 'required',
                ]);

                $post->update($request->all());

                return response(['post' => new PostResource($post), 'message' => 'Post Updated Successfully','status'=>'200']);

            }else{
                return response([ 'message' => 'You do not have rights to Update this post','status'=>'400']);
            }

        }else{

            $data = $request->validate([
                'title' => 'required',
                'post' => 'required',
            ]);

            $post->update($request->all());

            return response(['post' => new PostResource($post), 'message' => 'Post Updated Successfully','status'=>'200']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(auth()->guard('api')->user()->role == 1){
            if($post->user_id == auth()->guard('api')->user()->id){
                
               $post->delete();

                return response(['message' => 'Post deleted Successfully','status'=>'200']);

            }else{
                
                return response([ 'message' => 'You do not have rights to Delete this post','status'=>'400']);
            }
            
        }else{

            $post->delete();
            return response(['message' => 'Post deleted Successfully','status'=>'200']);
        }
    }
}
