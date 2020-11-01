<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
	protected $table = 'accesses';

    protected $fillable = [
        'user_id', 'profile_id'
    ];

	public function getUserAttribute()
	{
		return User::findOrFail($this->user_id);
	}

}
