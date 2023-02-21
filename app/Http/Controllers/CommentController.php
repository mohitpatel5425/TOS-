<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommentResource;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->guard('api')->user()->role == 0){

            $data = $request->all();
            $validator = Validator::make($data, [
                'comment' => 'required',
                'post_id' => 'required|integer|min:1',
            ]);

            if($validator->fails()){
                return response(['error' => $validator->errors(), 
                'Validation Error']);
            }
            $postDetails = Post::find($data['post_id']);

            if(!empty($postDetails) && !is_null($postDetails)){

                $comment = Comment::create($data);

                return response([ 'post' => new 
                CommentResource($comment), 
                'message' => 'Comment Added Successfully.','status'=>'200']);

            }else{

                return response(['message' => 'Post not available, Please check post ID.','status'=>'400']);
            }

        }else{

            return response([ 'message' => 'You do not have rights to Add Comment','status'=>'400']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
