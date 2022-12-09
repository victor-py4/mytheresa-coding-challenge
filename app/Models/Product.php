<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Discount
 *
 * @package \App\Models
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'discount_id',
        'price'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function category(): HasOne {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function discount(): HasOne {
        return $this->hasOne(Discount::class, 'id', 'discount_id');
    }
}
