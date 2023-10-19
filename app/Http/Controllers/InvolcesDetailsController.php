<?php

namespace App\Http\Controllers;

use App\Models\involces_details;
use App\Models\involces_attachment;
use App\Models\invoices;
use Illuminate\Http\Request;

class InvolcesDetailsController extends Controller
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
    public function show(involces_details $involces_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $involces = invoices::where('id',$id)->first();
        $details = involces_details::where('id_Invoice',$id)->get();
        $attachment = involces_attachment::where('invoice_id',$id)->get();
        return view('involces.involces_details', compact('involces','details','attachment')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, involces_details $involces_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(involces_details $involces_details)
    {
        //
    }
}
