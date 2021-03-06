<?php

namespace risul\LaravelLikeComment\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laravellikecomment_likes';

	protected $dates = ['created_at','updated_at'];

    /**
	 * Fillable array
     */
    protected $fillable = ['user_id', 'item_id', 'vote'];
}
