<?php

namespace IndicativeWp\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
	public $timestamps = false;
	
	protected $table = 'usermeta';
	protected $primaryKey = 'umeta_id';
	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
