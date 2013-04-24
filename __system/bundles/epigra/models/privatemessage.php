<?php

class PrivateMessage Extends Eloquent
{
	public static $table = 'messages';
	public static $timestamps = true;

	public function sender()
	{
		return $this->belongs_to('User','user_id');
	}

	public function receiver()
	{
		return $this->belongs_to('User','to_user_id');
	}
}