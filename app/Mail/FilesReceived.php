<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FilesReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Información del proovedor';
    public $xml_name;
    public $name_xml_file;
    public $pdf_name;
    public $name_pdf_file;
    public $other_name;
    public $name_other_file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($xml_name, $name_xml_file, $pdf_name, $name_pdf_file, $other_name, $name_other_file)
    {
        $this->xml_name = $xml_name;
        $this->name_xml_file = $name_xml_file;
        $this->pdf_name = $pdf_name;
        $this->name_pdf_file = $name_pdf_file;
        $this->other_name = $other_name;
        $this->name_other_file = $name_other_file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.files-received')->attach(
                asset($this->xml_name) , [
                    'as' => $this->name_xml_file,
                    'mime' => 'application/xml',
                ],
                // asset($this->xml_name), asset($this->xml_name),
            )->attach(
                asset($this->pdf_name) , [
                'as' => $this->name_pdf_file,
                'mime' => 'application/pdf',
            ]);
        // return $this->view('email.files-received');
    }
}
