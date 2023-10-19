<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class ReportControoler extends Controller
{
    public function index(){
        return view('report.index');
    }
    public function search_invoices(Request $request){

       $rdio = $request->rdio;
       $type = $request->type;
       $star = $request->start_at;
       $end = $request->end_at;
       $num =$request->invoice_number ;
     if ($rdio == 1){
         if($type && $star == '' && $end == ''){
            $involces = invoices::select('*')->where('Status', '=' , $type )->get();
            return view('report.index' , compact('type' ))->withDetails($involces);


         }
         elseif($type == 4 ){
            $involces = invoices::all()->get();
            return view('report.index' , compact('type' ))->withDetails($involces);

         }
         else{
            $involces = invoices::wherebetween('invoice_Date',[$star,$end])->where('Status','=',$type)->get();
            return view('report.index' , compact('type' , 'star', 'end' ))->withDetails($involces);


         }

       }
       else{
        $involces = invoices::where('invoice_number', '=',$num)->get();
        return view('report.index' , compact('num' ))->withDetails($involces);


       }
    }
}
