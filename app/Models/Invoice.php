<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'appointment_id',
        'product_id',
        'partner_id',
        'type',
        'fiscal_year',
        'status'
    ];
}
