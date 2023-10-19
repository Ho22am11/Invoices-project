<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;

class UserReportConrroller extends Controller
{
    public function index(){
        $sections= sections::all();
        return view('report.user', compact('sections'));
    }
    public function ser(Request $request){

        $sec = $request->Section;
        $pro = $request->product;
        $star = date($request->start_at);
        $end = date($request->end_at);


        if ($sec && $pro && $star == "" && $end=="") {

            $invoices = invoices::select('*')->where('section_id','=',$sec)->where('product','=',$pro)->get();
            $sections= sections::all();
            return view('report.user', compact('sections'))->withDetails($invoices);


        }
        elseif($sec && $pro == ""  && $star == "" && $end==""){
            $invoices = invoices::select('*')->where('section_id','=',$sec)->get();
            $sections= sections::all();
            return view('report.user', compact('sections'))->withDetails($invoices);

        }
        else{
            $invoices = invoices::select('*')->where('section_id','=',$sec)->where('product','=',$pro)->wherebetween('invoice_Date',[$star,$end])->get();
            $sections= sections::all();
            return view('report.user', compact('sections'))->withDetails($invoices);

        }


    }
}
