<?php namespace App\Repositories;

use App\Product;
use App\Selection;
use App\Tag;
use Illuminate\Support\Str;

class SelectionRepository
{
    public static function build($title, Array $tags)
    {
        $selection = Selection::create([
            'title' => $title,
            'slug' => Str::slug($title),
        ]);

        // Get the tag IDs for syncing
        $tagIds = Tag::whereIn('slug', $tags)->get()->lists('id');

        // Bind the tags to the new selection
        $selection->tags()->sync($tagIds);


        $products = Product::whereHas('tags', function($q) use ($tags) {
            $q->whereIn('id', $tags->lists('id'));
        })->get();

        return $selection;
    }
}