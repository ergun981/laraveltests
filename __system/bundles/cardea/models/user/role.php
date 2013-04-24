<?php 
namespace User;
use Eloquent;
class Role extends Eloquent {

    /**
    * The name of the table associated with the model.
    *
    * @var string
    */
    public static $table = 'user_roles';

    /**
    * Indicates if the model has update and creation timestamps.
    *
    * @var bool
    */
    public static $timestamps = false;

    public function users() {
    	return $this->has_many('User');
    }


}