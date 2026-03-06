<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
        'cover',
        'address',
        'city',
        'zip_code',
        'id_proof',
        'id_proof_back',
        'bank_name',
        'bank_ifsc',
        'bank_account_number',
        'bank_customer_name',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function cityDetails()
    {
        return $this->belongsTo(Cities::class, 'city');
    }
}
