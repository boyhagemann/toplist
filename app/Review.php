<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 *
 * @package App
 * @property int $id
 * @property string $body
 * @property int $rating
 * @property User $user
 * @property Product $product
 */
class Review extends Model
{
    protected $fillable = ['body', 'rating'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}