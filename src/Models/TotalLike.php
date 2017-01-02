<?php

namespace risul\LaravelLikeComment\Models;

use Illuminate\Database\Eloquent\Model;

class TotalLike extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laravellikecomment_total_likes';

	protected $dates = ['created_at','updated_at'];

    /**
	 * Fillable array
     */
    protected $fillable = [];
}
