<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\Types\This;

class FilesReceived extends Mailable
{
    use Queueable, SerializesModels;

    
    public $xml_name;
    public $name_xml_file;
    public $pdf_name;
    public $name_pdf_file;
    public $other_name;
    public $name_other_file;
    public $name_provider;
    public $other_file_aux;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($xml_name, $xml_original_name, $pdf_name, $pdf_original_name, $other_name, $name_other_file, $name_provider, $other_file_aux)
    {
        $this->xml_name = $xml_name;
        $this->xml_original_name = $xml_original_name;
        $this->pdf_name = $pdf_name;
        $this->pdf_original_name = $pdf_original_name;
        $this->other_name = $other_name;
        $this->name_other_file = $name_other_file;
        $this->name_provider = $name_provider;
        $this->other_file_aux = $other_file_aux;
        $this->subject = 'Información del proveedor' . ' ' . $this->name_provider;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd(asset($this->xml_name));
        if ($this->name_other_file == '') {
            return $this->view('email.files-received')->attach(  
                asset($this->xml_name) , [
                    'as' => $this->xml_original_name,
                    'mime' => 'application/xml',
                ],
                // asset($this->xml_name), asset($this->xml_name),
            )->attach(
                asset($this->pdf_name) , [
                'as' => $this->pdf_original_name,
                'mime' => 'application/pdf',
            ]);
        } else {
            return $this->view('email.files-received')->attach(
                asset($this->xml_name) , [
                    'as' => $this->xml_original_name,
                    'mime' => 'application/xml',
                ],
                // asset($this->xml_name), asset($this->xml_name),
            )->attach(
                asset($this->pdf_name) , [
                'as' => $this->pdf_original_name,
                'mime' => 'application/pdf',
            ])->attach(
                asset($this->other_name) , [
                'as' => $this->other_file_aux,
                'mime' => 'application/' . pathinfo($this->other_file_aux, PATHINFO_EXTENSION),
            ]);
        }

        //! Así debe de ir en Producción
        // if ($this->name_other_file == '') {
        //     return $this->view('email.files-received')->attach(  
        //         public_path($this->xml_name) , [
        //             'as' => $this->name_xml_file,
        //             'mime' => 'application/xml',
        //         ],
        //         // asset($this->xml_name), asset($this->xml_name),
        //     )->attach(
        //         public_path($this->pdf_name) , [
        //         'as' => $this->name_pdf_file,
        //         'mime' => 'application/pdf',
        //     ]);
        // } else {
        //     return $this->view('email.files-received')->attach(
        //         public_path($this->xml_name) , [
        //             'as' => $this->name_xml_file,
        //             'mime' => 'application/xml',
        //         ],
        //         // asset($this->xml_name), asset($this->xml_name),
        //     )->attach(
        //         public_path($this->pdf_name) , [
        //         'as' => $this->name_pdf_file,
        //         'mime' => 'application/pdf',
        //     ])->attach(
        //         public_path($this->other_name) , [
        //         'as' => $this->name_other_file,
        //         'mime' => 'application/' . pathinfo($this->other_file_aux, PATHINFO_EXTENSION),
        //     ]);
        // }
    }
}
