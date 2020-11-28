<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthToken extends Controller
{
    public function val($r,$arr,$funct){


    }
    public function register(Request $r){
        $val = Validator::make($r->all(),[
            'name'=>'required|min:3|max:30',
            'email'=>'required|unique:users',
            'password'=>'required|min:3|max:255'
        ]);
        if ($val->fails()) {
            return response()->json($val->errors(),400);
        }else{
            $token = bcrypt(rand(1, 1000000));
            User::create([
                'name'=>$r->name,
                'email'=>$r->email,
                'password'=>bcrypt($r->password),
                'token'=>$token
            ]);
            return response()->json(['msg'=>'success','token'=>$token],200);
        }

    }
    public function jsonT($payload = [],$status = 200){
        return response()->json($payload,$status);
    }

    public function Auth($email,$pass){
        $user = User::where('email',$email)->first();
        $pass = Hash::check($pass, $user->password);
        if ($user && $pass) {
            return $this->jsonT([
                'msg'=>'Authorization Successful!',
                'token'=>$user->token,
                'name'=>$user->name,
                'email'=>$user->email
            ]);
        }else{
            return $this->jsonT([
                'msg'=>'Unauthorized, check your credentials.'
            ],401);
        }
    }

    public function login(Request $r){
        $val = Validator::make($r->all(),[
            'email'=>'required',
            'password'=>'required|min:3|max:255'
        ]);
        if ($val->fails()) {
            return response()->json($val->errors(),400);
        }else{
            return $this->Auth($r->email,$r->password);
        }

    }

    public function logout(Request $r){
        if (User::where('token','=',$r->token)->exists()) {
            $n = User::where('token','=',$r->token)->update([
                'token'=>bcrypt(rand(1, 1000000))
            ]);
            return $this->jsonT(['msg'=>'success','sdf'=>$n]);
        }
        return $this->jsonT([
            'msg'=>'Unauthorized, check your credentials.'
        ],401);
    }

    public function AuthCheck(Request $r){
        $x = Validator::make($r->all(),[
            "token"=>'required'
        ]);
        if ($x->fails()) {
            return response()->json(
                $x->errors(),
            400);
        } else {
            $user = User::where('token', '=' , $r->token)->exists();
            $g =User::where('token', '=' , $r->token)->first();

            if ($user) {
                return response()->json([
                    'token' => $r->token,
                    'status' => $user,
                    'name' => $g->name,
                    'email' => $g->email
                ]);
            } else {
                return response()->json([
                    'msg'=>'Unauthorized',
                    'status'=>false
                ],401);
            }
        }


    }
}
