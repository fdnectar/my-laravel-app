<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'particular',
        'quantity',
        'unit_price',
        'total_price'
    ];
}
