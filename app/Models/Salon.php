<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Salon extends Model
{
    use HasFactory;

    protected $table = 'salon';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['uid','name','cover','categories','address','lat','lng','about','rating','total_rating','website','timing','images',
    'zipcode','service_at_home','verified','cid','have_stylist','status','in_home','popular','have_shop','extra_field', 'id_proof', 'upgrade', 
    'upgrade_date', 'bank_name', 'bank_ifsc', 'bank_account_number', 'bank_customer_name', 'agent_id', 'id_proof_back', 'heard_us_from',
    'executive_id', 'whatsapp_number', 'team_size', 'filters', 'facilities', 'pan', 'vat', 'invoice_prefix', 'fee_start'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'uid' => 'integer',
        'cid' => 'integer',
        'total_rating' => 'integer',
        'service_at_home' => 'integer',
        'verified' => 'integer',
        'have_stylist' => 'integer',
        'status' => 'integer',
    ];

    public function user():HasOne
    {
        return $this->hasOne(User::class, 'uid', 'id');
    }

    public function city():BelongsTo
    {
        return $this->belongsTo(Cities::class, 'cid', 'id');
    }
}
