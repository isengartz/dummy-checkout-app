<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
        'total_product_value',
        'total_shipping_value',
        'client_name',
        'client_address'
    ];

    /**
     * Accessor so we can show the price as float in the frontend
     * @param $value
     * @return float
     */
    public function getTotalProductValueAttribute($value) : float
    {
        return AppHelper::normalizePriceData($value);
    }

    /**
     * Mutator so we can store the price as integer
     * @param $value
     */
    public function setTotalProductValueAttribute($value) : void
    {
        $this->attributes['total_product_value'] = AppHelper::denormalizePriceData($value);
    }

    /**
     * Accessor so we can show the price as float in the frontend
     * @param $value
     * @return float
     */
    public function getTotalShippingValueAttribute($value) : float
    {
        return AppHelper::normalizePriceData($value);
    }

    /**
     * Mutator so we can store the price as integer
     * @param $value
     */
    public function setTotalShippingValueAttribute($value) : void
    {
        $this->attributes['total_shipping_value'] = AppHelper::denormalizePriceData($value);
    }
}
