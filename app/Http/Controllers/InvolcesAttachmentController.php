<?php

namespace App\Http\Controllers;

use App\Models\involces_attachment;

use App\Models\invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class InvolcesAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(involces_attachment $involces_attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(involces_attachment $involces_attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, involces_attachment $involces_attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(involces_attachment $involces_attachment)
    {
        //
    } 
    public function open(Request $request )
    {
        

       # return Storage::makeDirectory($invoice_number); // create file\
       if ($request->hasFile('pic')) {
       $invoice_id = invoices::latest()->first()->id;
       $attachments = new involces_attachment(); 
       $image = $request->file('pic');
       $file_name = $image->getClientOriginalName();
       $attachments -> file_name =   $file_name ;
       $attachments -> invoice_number = $request->invoice_number ;
       $attachments -> Created_by = Auth::user()->name; 
       $attachments -> invoice_id =  $request->invoice_id;
       $attachments->save();    

       $file =$request->file('pic');
       $invoice_num = $request->invoice_number;
       $path = 'Attachments'.'/'.$invoice_num;
       $request->pic->move($path,$file_name);

      session()->flash('Add', 'تم اضافة الملف بنجاح');
       }
      
   
      return back();

    }
 
    public function down(Request $request ,$invoice_number , $file_name){
       




        return Storage::download(url('Attachments/'.$invoice_number.'/'.$file_name));

    }
    public function del(Request $request ,$invoice_number , $file_name){
        $url = str('Attachments'.'/'.$invoice_number.'/'.$file_name);
        echo $url ;
    
         return Storage::download($url);


    }
}
