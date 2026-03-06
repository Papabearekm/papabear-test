<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
    protected $fillable = ['uid', 'amount', 'withdrawal_date', 'cod_commission', 'paid_cod_commission', 'status',];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}
