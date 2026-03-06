<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointments extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['uid','freelancer_id','salon_id','specialist_id','appointments_to','address','items','coupon_id','coupon',
    'discount','distance_cost','total','serviceTax','grand_total','pay_method','paid','save_date','slot','wallet_used',
    'wallet_price','notes','status','extra_field'];

    protected $hidden = [
        'updated_at',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'id');
    }

    public function freelancer()
    {
        return $this->belongsTo(Salon::class, 'freelancer_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(Payments::class, 'pay_method', 'id');
    }
}
