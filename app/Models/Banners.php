<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banners extends Model
{
    use HasFactory;

    protected $table = 'banners';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['city_id', 'user_id', 'position', 'title', 'price', 'cover','type','value', 'link', 'from','to','status','extra_field','lat','lng'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function city():BelongsTo
    {
        return $this->belongsTo(Cities::class, 'city_id', 'id');
    }
}
