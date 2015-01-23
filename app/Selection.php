<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Selection
 *
 * @package App
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property Tag[] $tags
 * @property Product[] $products
 */
class Selection extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}