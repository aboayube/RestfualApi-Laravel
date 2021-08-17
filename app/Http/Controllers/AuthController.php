<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{

  public function register(Request $request){

      $filed=$request->validate([
          'name'=>'required|string',
          'email'=>'required|string|unique:users,email',
          'password'=>'required|string|confirmed'
      ]);
      $user=User::create([
          'name'=>$filed['name'],
          'email'=>$filed['email'],
          'password'=>bcrypt($filed['password']),
      ]);
      $token=$user->createToken('myapptoken')->plainTextToken;

      $response=[
          'user'=>$user,
          'token'=>$token,
      ];

      return response($response,201);
  }

  public function login(Request $request){

    $filed=$request->validate([
        'email'=>'required',
        'password'=>'required'
    ]);
    $user=User::where('email',$filed['email'])->first();

        if(!$user || !Hash::check($filed['password'],$user->password)){
       
        
        return response([
            'message'=>'bad  creds',]
        ,401);
    }
    
return $user;
    $token=$user->createToken('myapptoken')->plainTextToken;

    $response=[
        'user'=>$user,
        'token'=>$token,
    ];

    return response($response,201);
}
  public function logout(Request $request){
      auth()->user()->tokens()->delete();
      return [
          'message'=>'logged out'
      ];
  }
    //
}
