<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegisterRequest extends Model
{
    use HasFactory;

    protected $table = 'register_request';

    public $timestamps = true; //by default timestamp false

    protected $fillable = [
        'first_name',
        'last_name',
        'country_code',
        'mobile',
        'cover',
        'gender',
        'type',
        'zipcode',
        'categories',
        'extra_field',
        'status',
        'email',
        'password',
        'address',
        'lat',
        'lng',
        'name',
        'about',
        'fee_start',
        'cid',
        'id_proof',
        'id_proof_back',
        'bank_name',
        'bank_ifsc',
        'bank_account_number',
        'bank_customer_name',
        'heard_us_from',
        'executive_id',
        'whatsapp_number',
        'team_size',
        'pan',
        'vat',
    ];

    public function city():BelongsTo
    {
        return $this->belongsTo(Cities::class, 'cid', 'id');
    }
}
