<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSubCategory extends Model
{
    use HasFactory;

    protected $table = 'product_sub_category';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['name','cover','cate_id','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function category():BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'cate_id', 'id');
    }
}
