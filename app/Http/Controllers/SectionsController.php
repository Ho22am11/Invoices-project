<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */


        function __construct()
{
    $this->middleware('permission:الاقسام', ['only' => ['index']]);
    $this->middleware('permission:اضافة قسم', ['only' => ['store']]);
    $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
    $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);

}


    public function index()
    {
        $sections = sections::all();
        return view('section.section', compact('sections'));
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

           $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
  
           ],[
                 'section_name.required' => 'فشل الاضافه  رجاءادخل اسم القسم',
                 'section_name.unique' => 'فشل الاضافه القسم موجود بلفعل',
               
           ]);

            sections::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'who_created' => (Auth::user()->name),

            ]);
            session()->flash('Add', 'تم اضافة القسم بنجاح ');
            return redirect('/section');
    }

    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        ]);

        $sections = sections::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','تم تعديل القسم بنجاج');
        return redirect('/section');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/section');
    }
}
