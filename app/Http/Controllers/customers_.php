<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customers;
use Illuminate\Support\Facades\Validator;

class customers_ extends Controller
{
    public function getCustomers(){
        return response()->json(customers::orderBy('created_at','DESC')->get());
    }
    public function createCustomers(Request $r){
        $val = Validator::make($r->all(),[
            'name'=>'required',
            'phone'=>'required|numeric',
            'address'=>'required',
            'email'=>'required|email|unique:customers'
        ]);
        if ($val->fails()) {
            return response()->json($val->errors(),400);
        }else {
            customers::create([
                'name'=>$r->name,
                'phone'=>$r->phone,
                'address'=>$r->address,
                'email'=>$r->email
            ]);

            return response()->json(['msg'=>'success'],200);
        }
    }

    public function getId($id){
        if (customers::where('id','=',$id)->exists()) {
            return response()->json(customers::where('id','=',$id)->first());
        }else {
            return response()->json(['msg'=>'not found'],404);
        }
    }
    public function updateCustomers(Request $r, $id){
        $h =customers::where('id','=',$id)->exists();
        if ($h) {
            $val = Validator::make($r->all(),[
                'name'=>'required',
                'phone'=>'required|numeric',
                'address'=>'required',
                'email'=>'required|email'
            ]);
            if ($val->fails()) {
                return response()->json($val->errors(),400);
            }else{
                customers::where('id','=',$id)->update([
                    'name'=>$r->name,
                    'phone'=>$r->phone,
                    'address'=>$r->address,
                    'email'=>$r->email
                ]);
                return response()->json(['msg'=>'success'],200);
            }
        }else{
            return response()->json(['msg'=>'not found'],404);
        }
    }
    public function deleteCustomers($id){
        $h = customers::where('id','=',$id)->exists();
        if ($h) {
            $n = customers::where('id','=',$id);
            $n->delete();
            return response()->json(['msg'=>'success'],200);
        }else {
            return response()->json(['msg'=>'not found'],404);
        }
    }
}
