<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\product;
class product_ extends Controller
{
    public function jsonT($payload = [],$status = 200){
        return response()->json($payload,$status);
    }
    public function showProduct(){
        $g = product::orderBy('created_at','DESC')->get();
        return $this->jsonT($g,200);
    }

    public function saveProduct(Request $r){
        $val = Validator::make($r->all(),[
            'title'=>'required',
            'discription'=>'required',
            'price'=>'required|numeric',
            'stock'=>'required|numeric',
        ]);
        if ($val->fails()) {
            return $this->jsonT($val->errors(),400);
        }else{
            product::create([
                'title'=>$r->title,
                'discription'=>$r->discription,
                'price'=>$r->price,
                'stock'=>$r->stock
            ]);
            return $this->jsonT(['msg'=>'success'],200);
        }
    }
    public function getID($id){
        $k = product::where('id','=',$id)->first();
        return $this->jsonT($k,200);
    }
    public function updateProduct(Request $r,$id){
        if (product::where('id','=',$id)->exists()) {
            $val = Validator::make($r->all(),[
                'title'=>'required',
                'discription'=>'required',
                'price'=>'required|numeric',
                'stock'=>'required|numeric',
            ]);
            if ($val->fails()) {
                return $this->jsonT($val->errors(),400);
            }else{
                product::where('id','=',$id)->update([
                    'title'=>$r->title,
                    'discription'=>$r->discription,
                    'price'=>$r->price,
                    'stock'=>$r->stock
            ]);
                return $this->jsonT(['msg'=>'success'],200);
            }
        }else {
            return $this->jsonT(['msg'=>'not found'],404);
        }
    }
    public function deleteProduct($id){
        if (product::where('id','=',$id)->exists()) {
            product::where('id','=',$id)->delete();
            return $this->jsonT(['msg'=>'success'],200);
        }else {
            return $this->jsonT(['msg'=>'not found'],404);
        }
    }
}
