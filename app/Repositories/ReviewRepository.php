<?php namespace App\Repositories;

use App\Review;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository
{
    /**
     * Create a new review written by a randomly picked user.
     *
     * @param Product $product
     * @param         $body
     * @return static
     */
    public static function create(Product $product, $body)
    {
        return Review::create([
            'product_id'    => $product->id,
            'user_id'       => UserRepository::random()->id,
            'body'          => $body,
            'rating'        => static::randomRating(),
        ]);
    }

    /**
     * Get a random weighted rating from 1 to 10
     *
     * @return integer
     */
    public static function randomRating()
    {
        $weighted = [
            1 => 50,
            2 => 10,
            3 => 20,
            4 => 30,
            5 => 50,
            6 => 80,
            7 => 60,
            8 => 100,
            9 => 80,
            10 => 60,
        ];

        $scores = new \Illuminate\Support\Collection();
        foreach($weighted as $rating => $weight) {
            for($i = 0; $i < $weight; $i++) {
                $scores->add($rating);
            }
        }

        return $scores->random();
    }
}