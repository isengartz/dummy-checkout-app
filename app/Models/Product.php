<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Dont add Timestamps
     * @var bool
     */
    public bool $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'name',
        'price',
        'brand_id'
    ];

    /**
     * Accessor so we can show the price as float in the frontend
     * @param $value
     * @return float
     */
    public function getPriceAttribute($value) : float {
        return $value / 100;
    }

    /**
     * Mutator so we can store the price as integer
     * @param $value
     */
    public function setPriceAttribute($value) : void  {
        $this->attributes['price'] = number_format($value,2) * 100;
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
