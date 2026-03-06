<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['uid','title','message','appointment_id','type','status'];

    protected $hidden = [
        'appointment_id','updated_at', 'created_at',
    ];
}
