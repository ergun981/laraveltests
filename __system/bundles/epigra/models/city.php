<?php

class City extends Eloquent{

	public static $table = 'cities';
	public static $timestamp = false ;

	public function users(){

		return $this->has_many('User');

	}

}

