<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserController as UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->guard('api')->user()->role == 1){
                
            $users = User::where('id',auth()->guard('api')->user()->id)->get();
            return response(['users'=>UserResource::collection($users),'message'=>'Successful','status'=>'200']);

        }else{

            $users = User::all();
            return response([ 'users' => 
            UserResource::collection($users), 
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {   
        return response([ 'user' => new UserResource($user), 'message' => 'User Details Fetched Successfully','status'=>'200']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {   
        $data = $request->validate([
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'phone' => 'required|digits:10',
            'role' => 'required|digits:1',
        ]);

        $user->update($request->all());

        return response([ 'user' => new UserResource($user), 'message' => 'User Updated Successfully','status'=>'200']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(['message' => 'User deleted Successfully','status'=>'200']);
    }
}
