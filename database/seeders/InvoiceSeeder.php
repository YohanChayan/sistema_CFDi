<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Invoice::factory()->create([
            'provider_id' => 1,
            'owner_id' => 2,
            'uuid' => 'ed62f2d4-a03f-4c82-b075-aaca426826e0',
            'folio' => '565',
            'total' => 10000.00,
            'status' => 'Pendiente',
            'pdf' => 'archivos/pdf/ed62f2d4-a03f-4c82-b075-aaca426826e0.pdf',
            'xml' => 'archivos/xml/ed62f2d4-a03f-4c82-b075-aaca426826e0.xml',
            'other' => 'archivos/anexo/anexo.png',
        ]);

        Invoice::factory()->create([
            'provider_id' => 2,
            'owner_id' => 1,
            'uuid' => 'c8efd0b8-7509-4bed-a0d6-7bea04bfad06',
            'folio' => '8074',
            'total' => 1800.01,
            'status' => 'Pendiente',
            'pdf' => 'archivos/pdf/c8efd0b8-7509-4bed-a0d6-7bea04bfad06.pdf',
            'xml' => 'archivos/xml/c8efd0b8-7509-4bed-a0d6-7bea04bfad06.xml',
            'other' => 'archivos/anexo/anexo.png',
        ]);

        Invoice::factory()->create([
            'provider_id' => 3,
            'owner_id' => 3,
            'uuid' => '25C4B8B8-C429-4A66-B44E-A73639A6D8D5',
            'folio' => '29536',
            'total' => 1495.00,
            'status' => 'Pendiente',
            'pdf' => 'archivos/pdf/25C4B8B8-C429-4A66-B44E-A73639A6D8D5.pdf',
            'xml' => 'archivos/xml/25C4B8B8-C429-4A66-B44E-A73639A6D8D5.xml',
            'other' => 'archivos/anexo/anexo.png',
        ]);
    }
}