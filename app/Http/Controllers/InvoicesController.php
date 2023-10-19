<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\involces_details;
use App\Models\involces_attachment;
use Illuminate\Support\Facades\Notification;
use App\Models\sections;
use App\Models\products;
use App\Notifications\InvoicePaid;
use App\Notifications\AddInvoice;
use App\Notifications\InvoicePaidtwo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Notifications\Notifiable;

class InvoicesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
        $this->middleware('permission:ارشيف الفواتير', ['only' => ['index2']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['forcedelete']]);
        $this->middleware('permission:الفواتير المدفوعة', ['only' => ['paied']]);
        $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['unpaied']]);
        $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['hafpaied']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['indexadd']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['show']]);
        $this->middleware('permission:طباعةالفاتورة', ['only' => ['print']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['Status_Update']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['store']]);
        $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
        $this->middleware('permission:ارشفة الفاتورة', ['only' => ['delete']]);
    }

    public function index()
    {

        $involces = invoices::all();

        return view('involces.involces',compact('involces'));
    }
    public function index2()
    {

        $trashedinvolces = invoices::onlyTrashed()->get();
        return view('involces.delete_involces',compact('trashedinvolces'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function forcedelete(Request $request)
    {
         $id = $request->invoice_id;
         $invoice_number = $request->invoice_number;
        $invoices = invoices::where('id', $id)->first();
         Storage::deleteDirectory($invoice_number);
         if ($invoices != null) {
              $invoices->ForceDelete();
         }

         session()->flash('delete_invoice');
         return redirect('/delete_involces');




    }
    public function paied()
    {
        $involces = invoices::where('Value_Status',1)->get();
        return view('involces.involces_paied',compact('involces'));
    }
    public function unpaied()
    {
        $involces = invoices::where('Value_Status',2)->get();
        return view('involces.involces_unpaied',compact('involces'));
    }
     public function hafpaied()
    {
        $involces = invoices::where('Value_Status',3)->get();
        return view('involces.involces_hafpaied',compact('involces'));
    }


     public function indexadd()
    {
        $sections = sections::all();
        return view('involces.add_involces',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->products,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,

        ]);


        $invoice_id = invoices::latest()->first()->id;
        involces_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->products,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),

        ]);


        if ($request->hasFile('pic')) {
            $invoice_id = invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number ;

            $attachments = new involces_attachment();
            $attachments -> file_name =  $file_name ;
            $attachments -> invoice_number = $invoice_number ;
            $attachments -> Created_by = Auth::user()->name;
            $attachments -> invoice_id =  $invoice_id;
            $attachments->save();


            Storage::makeDirectory($invoice_number);
            $path ='Attachments'.'/'.$invoice_number;
            $request->pic->move($path,$file_name);
            
         


        }
      //  $iddd = $request->Section ;
       // $sec = sections::where('id',$iddd)->first();
        $user = Auth::user();
    
        Notification::send($user, new InvoicePaid($invoice_id));

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = invoices::where('id',$id)->first();
        return view('involces.change_Status',compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $involces = invoices::where('id',$id)->first();
        $sections = sections::all();
        return view('involces.edit' , compact('involces','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updata(Request $request)
    {



            $invoices = invoices::findOrFail($request->id);
           $invoices->update([
            'invoice_number' => $request->invoice_number,
               'invoice_Date' => $request->invoice_Date,
               'Due_date' => $request->Due_date,
               'product' => $request->products,
            'section_id' => $request->Section,
              'Amount_collection' => $request->Amount_collection,
               'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
               'Value_VAT' => $request->Value_VAT,
               'Rate_VAT' => $request->Rate_VAT,
               'Total' => $request->Total,
            'note' => $request->note,
           ]);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id',$id)->first();

        $invoices->Delete();
        session()->flash('delete_invoice');
        return redirect('/involces');
    }
    public function getproducts($id)
    {
        $products = DB::table("products")->where("sections_id", $id)->pluck("products_name", "id");
        return json_encode($products);
    }
    public function Status_Update(Request $request , $id)
    {
        $invoices = invoices::findOrFail($id);
        if ($request->Status === 'مدفوعة'){
            $invoices->update([
                'Value_Status' => 1 ,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,


            ]);
            involces_details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
                'Value_Status' => 1,
                'note' => $request->note,
                'user' => (Auth::user()->name),

            ]);


        }
        else{
            $invoices->update([
                'Value_Status' => 3 ,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,


            ]);
            involces_details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
                'Value_Status' => 3,
                'note' => $request->note,
                'user' => (Auth::user()->name),

            ]);


        }

        $iddd = $request->Section ;
        $sec = sections::where('id',$iddd)->first();
        $invoice_id =$request->invoice_id ;
       Notification::send($sec, new InvoicePaidtwo($invoice_id));
       session()->flash('change_invoice');
      return redirect('/involces');
    }
    public function print($id)
    {
       $invoices = invoices::where('id',$id)->first();
       return view('involces.print',compact('invoices'));
    }
}
