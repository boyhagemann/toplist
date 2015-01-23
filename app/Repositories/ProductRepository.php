<?php namespace App\Repositories;

use App\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    /**
     * @param $slug
     * @return Product
     */
    public static function findBySlug($slug)
    {
        return Product::firstOrCreate(['slug' => $slug]);
    }

    /**
     * Only get products that matches all tags.
     *
     * @param Collection $tags
     * @return Product[]
     */
    public static function findByTags(Collection $tags)
    {
        return Product::whereHas('tags', function($q) use ($tags) {
            $q->whereIn('id', $tags->lists('id'));
        }, '=', count($tags))->get();
    }

    /**
     * @param Collection $tags
     * @param int        $max
     * @return Collection
     */
    public static function random(Collection $tags, $max = 5)
    {
        $products = static::findByTags($tags)->keyBy('id');
        $selected = new Collection();

        // Choose the right number of items
        $count = min($max, $products->count());


        for($i = 0; $i < $count; $i++) {

            // Select a random product
            $product = static::spliceRandomProduct($products);

            // Add it to the selection
            $selected->add($product);
        }

        return $selected;
    }

    /**
     * @param Collection $products
     * @return Product
     */
    public static function spliceRandomProduct(Collection $products)
    {
        // Take one random product out of the collection
        $product = $products->random();

        // Remove the taken product from the collection
        $products->forget($product->id);

        return $product;
    }
}