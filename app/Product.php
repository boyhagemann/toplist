<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @package App
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property Tag[] $tags
 * @property Offer[] $offers
 * @property Reviews[] $reviews
 */
class Product extends Model
{
    protected $fillable = ['slug', 'title', 'description', 'image'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany('App\Offer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}