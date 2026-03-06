<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    use HasFactory;

    protected $table = 'individual';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['uid','background','categories','address','about','rating','total_rating','website','timing','images',
    'zipcode','verified','cid','fee_start','lat','lng','status','in_home','popular','have_shop','extra_field','upgrade', 'upgrade_date',
    'bank_name', 'bank_ifsc', 'bank_account_number', 'bank_customer_name','id_proof', 'id_proof_back', 'heard_us_from',
    'executive_id', 'whatsapp_number', 'team_size', 'filters', 'facilities', 'pan', 'vat', 'invoice_prefix'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'uid' => 'integer',
        'cid' => 'integer',
        'total_rating' => 'integer',
        'verified' => 'integer',
        'status' => 'integer',
    ];

    public function city()
    {
        return $this->belongsTo(Cities::class, 'cid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
