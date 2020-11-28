<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoicedetail extends Model
{
    use HasFactory;
    protected $table = 'invoice_details';
    protected $fillable = [
        'product_id',
        'invoice_id',
        'price',
        'qty',
        'total'
    ];


}
