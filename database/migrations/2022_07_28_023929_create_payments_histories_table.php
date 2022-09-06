<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('approved_by')->constrained('users', 'id');
            $table->foreignId('invoice_id')->constrained('invoices', 'id');
            $table->date('date');
            $table->char('payment_method', 1)->default(1)->comment('1 = Efectivo, 2 = Tarjeta de crédito, 3 = Tarjeta de débito, 4 = Transferencia, 5 = Otro');
            $table->double('payment', 8, 2);
            $table->string('filePayment')->nullable();
            $table->string('receipt')->nullable()->comment('Comprobante de pago');
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
        Schema::dropIfExists('payments_histories');
    }
}
