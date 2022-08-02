<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $table = 'providers';
    
    protected $fillable = [
        'rfc',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function invoice() {
        return $this->hasOne(Invoice::class);
    }

}
