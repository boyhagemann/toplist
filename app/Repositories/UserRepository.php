<?php namespace App\Repositories;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class UserRepository
{
    /**
     * Pick a random user.
     *
     * @return User
     */
    public static function random()
    {
        return User::all()->random();
    }

    /**
     * Create a fake user.
     *
     * @param string $locale
     * @return User
     */
    public static function fake($locale = 'nl_NL')
    {
        $gender = Collection::make(['male', 'female'])->random();
        $faker = \Faker\Factory::create($locale);

        $user = User::firstOrNew(['email' => Str::random(32) . '@fake.com']);
        $user->name = $faker->name($gender);
        $user->gender = $gender;
        $user->image = static::fakeImage($gender);
        $user->save();

        return $user;
    }

    /**
     * Create multiple fake users.
     *
     * @param int    $count
     * @param string $locale
     * @return User[]
     */
    public static function fakeMultiple($count, $locale = 'nl_NL')
    {
        $collection = new Collection();
        for($i = 0; $i < $count; $i++) {
            $user = static::fake($locale);
            $collection->add($user);
        }

        return $collection;
    }

    /**
     * Get a fake user image.
     *
     * @param $gender
     * @return string
     */
    public static function fakeImage($gender)
    {
        $client = new Client;
        $url = 'http://api.randomuser.me?gender=' . $gender;
        $response = $client->get($url)->json();

        return $response['results'][0]['user']['picture']['large'];
    }

}