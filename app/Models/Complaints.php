<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    use HasFactory;

    protected $table = 'complaints';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['uid','order_id','appointment_id','complaints_on','issue_with','driver_id','freelancer_id','product_id','reason_id','title','short_message','images','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'status' => 'integer',
        'issue_with' => 'integer',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function freelancer() {
        return $this->belongsTo(User::class, 'freelancer_id', 'id');
    }

    public function salon() {
        return $this->belongsTo(Salon::class, 'salon_id', 'id');
    }

    public function product() {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function order() {
        return $this->belongsTo(ProductOrders::class, 'order_id', 'id');
    }

    
}
