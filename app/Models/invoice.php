<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $guard = [];
    protected $fillable = [
        'customers_id',
        'total',
        'codeinv'
    ];
}
