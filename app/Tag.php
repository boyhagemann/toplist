<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * @package App
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Tag[] $tags
 */
class Tag extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function products()
    {
        return $this->morphedByMany('App\Product', 'taggable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function selections()
    {
        return $this->morphedByMany('App\Selection', 'taggable');
    }
}