<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    // function login
    public function login(Request $request)
    {
        // dd($request->all());die();

        $user = User::where('email', $request->email)->first();

        // if user found
        if($user){
            // checking password
            if(password_verify($request->password, $user->password)){
                return response()->json([
                    'success' => 201,
                    'message' => 'WELCOME TO TRIAL API, '.$user->name.'!',
                    'user' => $user
                ]);
            }else{
                return response()->json([
                    'error' => 500,
                    'message' => 'PASSWORD IS NOT CORRECT!'
                ]);
            }

            
        }

        return response()->json([
            'error' => 500,
            'message' => 'USER NOT FOUND!'
        ]);        
    }

    // function register : name, email, password
    public function register(Request $request){
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8'
        ]);

        if($validated->fails()){
            $failed = $validated->errors()->all();
            return response()->json([
                'error' => 500,
                'message' => $failed
            ]);    
        }else{

            $user = User::create(array_merge($request->all(),[
                'password' =>bcrypt($request->password)
            ]));

            if($user){
                return response()->json([
                    'success' => 201,
                    'message' => 'YOUR REGISTRATION IS SUCCESSFUL!',
                    'user' => $user
                ]);
            }else{
                return response()->json([
                    'error' => 500,
                    'message' => 'YOUR REGISTRATION IS FAILED!',
                ]); 
            }
            
        }

    }

    // function edit profile user
    public function edit($id){
        $user = User::find($id);

        // jika user ada
        if($user){
            return ;
        }
    }

    // need db:( lah

}
