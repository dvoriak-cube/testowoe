<?php

namespace App\Http\Controllers;

use App\User;
use App\OauthAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public $successStatus = 200;
    
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails())
        {
        return response(['errors'=>$validator->errors()->all()], 422);
        }
        
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            $userid = Auth::id();
            $token = OauthAccessToken::where('user_id', $userid)->where('revoked', 0)->first();
            if(empty($token)){
                $success['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response(['success' => $success], $this-> successStatus);
            }
            else{    
                $success['token'] = $token->id;
                return response(['Already logged in' => $success], $this-> successStatus);
            }
        } 
        else{ 
            return response(['error'=>'Unauthorised'], 401); 
        } 
    }
    
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:users', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response(['errors'=>$validator->errors()->all()], 422); 
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']);
        $input['remember_token'] = Str::random(10); 
        $user = User::create($input); 
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $success['token'] =  $token; 
        $success['name'] =  $user->name;
        return response(['success'=>$success], $this-> successStatus); 
    }
    
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        if(Auth::check()){
        $user = Auth::user(); 
        return response(['success' => $user], $this-> successStatus);
        }
        else{
            return response(['error'=>'Unauthorised'], 401);
        } 
    } 

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
