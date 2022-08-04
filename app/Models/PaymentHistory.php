<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;
    protected $table = 'payments_histories';

    public function invoice() {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
