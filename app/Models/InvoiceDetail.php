<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $table = 'invoices_details';

    public function invoice() {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function sat_product() {
        return $this->belongsTo(SatProduct::class, 'sat_product_id');
    }

    public function sat_measurement_unit() {
        return $this->belongsTo(SatMeasurementUnit::class, 'sat_measurement_unit_id');
    }
}
