<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalonService extends Model
{
    use HasFactory;

    protected $table = 'salon_services';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['uid','service_id','cover','duration','price','off','discount','descriptions','images','status','extra_field', 'gender'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'uid' => 'integer',
        'service_id' => 'integer',
        'status' => 'integer',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function service():BelongsTo
    {
        return $this->belongsTo(Services::class, 'service_id', 'id');
    }
}
