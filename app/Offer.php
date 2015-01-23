<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Offer
 *
 * @package App
 * @property int $id
 * @property float $price
 * @property string $url
 * @property Vendor $vendor
 * @property Product $product
 */
class Offer extends Model
{
    protected $fillable = ['price', 'url'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}