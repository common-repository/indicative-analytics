<?php

namespace IndicativeWp\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	public $timestamps = false;
	
	protected $primaryKey = 'ID';
	
	public function meta()
	{
		return $this->hasMany(UserMeta::class, 'user_id');
	}
}
