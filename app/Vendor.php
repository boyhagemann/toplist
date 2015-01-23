<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vendor
 *
 * @package App
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $image
 * @property string $url
 * @property Offer[] $offers
 */
class Vendor extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany('App\Offer');
    }

}