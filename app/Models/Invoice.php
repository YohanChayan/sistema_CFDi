<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';

    protected $fillable = [
        'uuid',
        'pdf',
        'xml',
    ];

    public function owner() {
        return $this->belongsTo(Owner::class);
    }

    public function provider() {
        return $this->hasOne(Provider::class);
    }
}
