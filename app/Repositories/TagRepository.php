<?php namespace App\Repositories;

use App\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TagRepository
{
    /**
     * @return Collection
     */
    public function all()
    {
        return Tag::all();
    }
    /**
     * @param array $tags
     * @return Collection
     */
    public static function save(Array $tags)
    {
        Tag::unguard();

        $collection = new Collection();

        foreach($tags as $name) {
            $slug = Str::slug($name);
            $tag = Tag::firstOrNew(['slug' => $slug]);
            $tag->name = $name;
            $tag->save();
            $collection->add($tag);
        }

        return $collection;
    }
}