<?php

namespace risul\LaravelLikeComment\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laravellikecomment_comments';

	protected $dates = ['created_at','updated_at'];

    /**
	 * Fillable array
     */
    protected $fillable = ['user_id', 'parent_id', 'item_id', 'comment'];
}
