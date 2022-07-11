<?php

namespace App\Http\Controllers;

use App\Models\Yohan;
use App\Models\Jesus;
use App\Models\Invoice;
use App\Models\Owner;
use App\Models\Provider;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
        // dd($request->file('other'));
        $validateData = $this->validate($request,[
            'pdf_input' => 'file|required|mimes:pdf',
            'xml_input' => 'file|required|mimes:xml',
            'other' => 'file',
        ]);
        $file_pdf = $request->file('pdf_input');
        $file_xml = $request->file('xml_input');

        

        $convertedPDF = Jesus::readPDF( $file_pdf );
        $convertedXML = Jesus::readXML( $file_xml );
        $filesCompared = Jesus::compareFiles($convertedPDF, $convertedXML);

            if($filesCompared){ // archivos iguales
                $uuid = Jesus::getUUID($convertedXML);   //Obtiene el UUID del archivo xml
                $provider_rfc = Jesus::getProviderRFC($convertedXML);   //Obtiene el RFC del emisor
                $owner_rfc = Jesus::getOwnerRFC($convertedXML);   //Obtiene el RFC del emisor

                $new_invoice = new Invoice();

                $name_pdf_file = time() . '.pdf';
                $file_pdf->move(public_path("archivos/pdf"), $name_pdf_file);
                $pdf_name = "archivos/pdf/" . $name_pdf_file;

                $name_xml_file = time() . '.xml';
                $file_xml->move(public_path("archivos/xml"), $name_xml_file);
                $xml_name = "archivos/pdf/" . $name_xml_file;
                

                if ($request->file('other') != null) {
                    $other_file = $request->file('other');
                    $other_file_aux = $other_file->getClientOriginalName();

                    $name_other_file = time() . '.' . pathinfo($other_file_aux, PATHINFO_EXTENSION);
                    $other_file->move(public_path("archivos/anexo"), $name_other_file);
                    $other_name = "archivos/anexo/" . $name_other_file;

                    $new_invoice->other = $other_name;
                    
                }
                

                $db_owner = Owner::where('rfc', $owner_rfc)->first();
                $search_provider = Provider::where('rfc', $provider_rfc)->first();   //Busca el RFC del emisor en la base de

                $invoice_uuid = Invoice::where('uuid', $uuid)->first();

                if($db_owner == null){
                    Alert::error('Error', 'El RFC del receptor no coincide con ninguna empresa');
                    return redirect()->back();
                }

                if ($invoice_uuid != null) {
                    Alert::error('Error', 'El UUID ya se encuentra registrado');
                    return redirect()->back();
                }

                if($search_provider != null)  // provider encontrado
                    $new_invoice->provider_id = $search_provider->id;
                else{
                    $newProvider = new Provider();
                    $newProvider->nombre = 'provider nombre';
                    $newProvider->rfc = $provider_rfc;
                    $newProvider->save();

                    $new_invoice->provider_id = $newProvider->id;
                }

                $new_invoice->owner_id = $db_owner->id;
                $new_invoice->uuid = $uuid;
                $new_invoice->pdf = $pdf_name;
                $new_invoice->xml = $xml_name;
                
                $new_invoice->save();
                // $new_invoice->other = pendiente

                Alert::success('Éxito', 'Factura guardada correctamente');
                return redirect()->back();

            }else{
                Alert::error('Error', 'Los archivos NO contienen el mismo UUID');
                return redirect()->back();
            }













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
