<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;


use App\Models\customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\invoice;
use App\Models\invoicedetail;
use App\Models\product;
use Illuminate\Support\Facades\DB;
class InvoiceHandel extends Controller
{
    public function CreateInv(Request $r){
        $n = Validator::make($r->all(),[
            'customers_id'=>'required|exists:customers,id'
        ]);
        if ($n->fails()) {
            return response()->json($n->errors(),400);
        } else {
            $j=invoice::create([
                'customers_id' => $r->customers_id,
                'total' => 0,
                'codeinv'=>$r->customers_id ."-". Str::random(5)
            ]);
            return response()->json([
                'msg' => 'Success',
                'id_invoice'=>$j->id
            ]);
        }
    }
    public function Invoicedetailsave(Request $r,$id){
        $t = Validator::make($r->all(),[
            'product_id'=>'required',
            'invoice_id'=>'required',
            'qty'=>'required',
        ]);

        if ($t->fails()) {
            return response()->json($t->errors(),400);
        }else {
            $n = product::where('id','=',$r->product_id)->first();
            if ($n->stock > $r->qty) {
                $n->update([
                    'stock'=>$n->stock - $r->qty
                ]);
            }else{

            }
            if($n->stock == 0){
                return response()->json([
                    0=>['Out Of Stock']
                ],400);
            }else{

                $f = invoice::where('id', '=', $id)->first();
                $f->update([
                    'total'=>$f->total + $r->qty
                ]);

                invoicedetail::create([
                    'product_id'=>$r->product_id,
                    'invoice_id'=>$r->invoice_id,
                    'price'=>$n->price,
                    'qty'=>$r->qty
                ]);
                return response()->json([
                    'msg' => 'Success',

                ]);
            }


        }
    }
    public function deletedetai($id,$idinv,$mnqty,$prdk_id){
        $n = product::where('id','=',$prdk_id)->first();
        $n->update([
            'stock'=>$n->stock + $mnqty
        ]);
        $f = invoice::where('id', '=', $idinv)->first();
                $f->update([
                    'total'=>$f->qty - $mnqty
                ]);
        invoicedetail::where('id','=',$id)->delete();
        return response()->json([
            'msg' => 'Success',
        ]);
    }
    public function getInvloce($id){
        $h = invoice::leftJoin('invoice_details',function($join){
            $join->on('invoices.id','=','invoice_details.invoice_id');
        })->leftJoin('products',function($join){
            $join->on('products.id','=','invoice_details.product_id');
        })->select('products.*','invoice_details.qty',
        'invoice_details.id as details_id',
        )->where('invoice_id','=',$id)->get();

        $u =  invoice::leftJoin('customers',function($join){
            $join->on('customers.id','=','invoices.customers_id');
        })->where('invoices.id','=',$id)->first([
            'invoices.id',
            'invoices.note',
            'invoices.status',
            'invoices.total',
            'customers.name',
            'customers.phone',
            'customers.address',
            'customers.email',
        ]);

        return response()->json([
            "invoice"=>$u,
            "detail"=>$h,
            "total_price"=> $h->sum('price'),
            "total_qty"=>$h->sum('qty'),
            "total_product"=> $h->count()
        ]);

        // return DB::select(" SELECT  invoices.id,invoices.note,invoices.status,invoices.total,customers.name,customers.phone ,customers.address ,customers.email
        // FROM invoices
        // LEFT JOIN customers ON customers.id = invoices.customers_id WHERE invoices.id = $id
        // ");

        // return DB::select(" SELECT  *  FROM invoices LEFT JOIN invoice_details
        // ON invoices.id = invoice_details.invoice_id
        // LEFT JOIN products ON products.id = invoice_details.product_id
        // LEFT JOIN customers ON customers.id =  invoices.customers_id
        //         ");
    }
    public function getinvcuslist(){
        $n = invoice::leftjoin('customers',function($join){
            $join->on('invoices.customers_id','=','customers.id');
        })->select('invoices.id as invoices_id','invoices.codeinv as code_invoice','customers.*')->get();
        return $n;
    }
    public function deleteinv($id){
        $t = invoicedetail::where('invoice_id','=',$id)->groupBy('product_id')
        ->selectRaw('sum(qty) as total,product_id')->get();
         foreach ($t as $key) {
             $h=product::find($key['product_id']);
             $h->update([
                 'stock'=>$h->stock + $key['total']
            ]);
         }
        invoice::where('id','=',$id)->delete();
        invoicedetail::where('invoice_id','=','$id')->delete();
        return response()->json([
            'msg' => 'Success',

        ]);
    }
    public function getinvDetails($id){
        $h = invoice::leftJoin('invoice_details',function($join){
            $join->on('invoices.id','=','invoice_details.invoice_id');
        })->leftJoin('products',function($join){
            $join->on('products.id','=','invoice_details.product_id');
        })->where('invoice_id','=',$id)->get();

        return response()->json($h);
    }
    public function DashboardData(){
        $n = invoice::count();
        $x = customers::count();
        $y = product::count();
        $h = invoice::select(DB::raw("DATE(created_at) as date"),DB::raw("count(*) as total"))
        ->groupBy('date')->get();
        $t = $h->avg('total');
        $g = invoice::avg('total');
        $i = product::avg('price');
        return response()->json([
            'total_invoices'=>$n,
            'total_customers'=>$x,
            'total_products'=>$y,
            'stat_invoice'=>$h,
            'average_day'=>$t,
            'average_cutomers_total_qty'=>$g,
            'average_product_price'=>$i
        ]);
    }

}
