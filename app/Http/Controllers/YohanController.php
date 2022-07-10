<?php

namespace App\Http\Controllers;

use App\Models\Yohan;
use App\Models\Invoice;
use Illuminate\Http\Request;

class YohanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('yohan.form_files');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $this->validate($request,[
            'pdf_input' => 'file|required|mimes:pdf',
            'xml_input' => 'file|required|mimes:xml',
        ]);

        $new_invoice = new Invoice();
        $name_pdf = $request->file('pdf_input')->getClientOriginalName();
        $name_xml = $request->file('xml_input')->getClientOriginalName();

        $new_invoice->pdf = $name_pdf;
        $new_invoice->xml = $name_xml;

        

        // dd($name_pdf,$name_xml);
        // $path = $request->file('file')->store('public/files');


    }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Yohan  $yohan
     * @return \Illuminate\Http\Response
     */
    public function show(Yohan $yohan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Yohan  $yohan
     * @return \Illuminate\Http\Response
     */
    public function edit(Yohan $yohan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Yohan  $yohan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Yohan $yohan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Yohan  $yohan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Yohan $yohan)
    {
        //
    }
}
