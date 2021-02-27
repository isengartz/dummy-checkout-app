<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    use HasFactory;

    /**
     * Dont add Timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'brand_id'
    ];

    /**
     * Accessor so we can show the price as float in the frontend
     * @param $value
     * @return float
     */
    public function getPriceAttribute($value) : float
    {
        return AppHelper::normalizePriceData($value);
    }

    /**
     * Mutator so we can store the price as integer
     * @param $value
     */
    public function setPriceAttribute($value) : void
    {
        $this->attributes['price'] = AppHelper::denormalizePriceData($value);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
