<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("provider_id")->constrained("providers", "id");
            $table->foreignId("owner_id")->constrained("owners", "id");
            $table->string("uuid");
            $table->string("folio")->nullable();
            $table->double("total", 8, 2);
            $table->string("status", 40)->default("Pendiente")->comment("Pendiente, Pagado");
            

            $table->string("pdf")->comment("Url del archivo pdf");
            $table->string("xml")->comment("Url del archivo xml");
            $table->string("other")->nullable()->comment("Url de un posible tercer archivo");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
