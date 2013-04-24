<?php 
namespace User;
use Eloquent;
class User extends Eloquent {

	public static $timestamps = true;
	public static $table = "users"; 

    public static $rules = array(
        'first_name'      => 'required',
        'last_name'       => 'required',
        'email'           => 'required|email|unique:users',
        'password'        => 'min:4', // |confirmed
        'activation_code' => '',
        'activated'       => 'integer',
        'expertise'       => 'required',
        'title'           => 'required',
        'city_id'         => 'required',
        'company'         => 'required',
        'privacy'         => 'required',
        );

    public static function validate($form){
        $validation = \Laravel\Validator::make($form, static::$rules);

        if($validation->valid()){
            return false;
        }else{
            return $validation->errors;
        }
    }

    public function get_activity_user(){
        return $this->has_many('Activity', 'user_id');
    }
    public function get_activity_to_user(){
        return $this->has_many('Activity', 'to_user_id');        
    }

    public function role() {		
        return $this->belongs_to('User\Role');
    }

    public function followings(){
        return $this->has_many_and_belongs_to('User', 'user_follow', 'followed_by', 'user_id');
    }
    public function followers(){
        return $this->has_many_and_belongs_to('User', 'user_follow', 'user_id', 'followed_by');
    }
    public function sended_messages(){
        return $this->has_many('PrivateMessage', 'user_id');
    }
    public function received_messages(){
        return $this->has_many('PrivateMessage', 'to_user_id');        
    }
    public function count_unreads(){
        return count($this->received_messages()->where_is_read('0')->group_by('user_id')->get());        
    }

    public function count_unreads_string(){
        $count = $this->count_unreads();
        if($count == 0) return '';
        return "(".$count.")";
    }
    public function count_follow_req(){
        return \Activity::where_to_user_id_and_action_id_and_content($this->id, 9, 'istek')->count();    
    }

    public function count_follow_req_string(){
        $count = $this->count_follow_req();
        if($count == 0) return '';
        return "(".$count.")";
    }
    /**
     * @param $key: string
     */
    public function has_role($key)
    {	
    	if($this->role->name == $key)
    	{
    		return true;
    	}

    	return false;
    }

    public function level()
    {   
        return $this->role->level;
    }

    public function role_name()
    {   
        return $this->role->name;
    }
    
    public function full_name()
    {
    	return $this->get_attribute('first_name') . ' ' . $this->get_attribute('last_name');
    }

    public function set_password($pwd)
    {
    	$this->set_attribute('password', \Hash::make($pwd));
        $this->save();
    }

    public static function generate_activation()
    {
    	return \Str::random(32);
    }

	/**
	 * Resets the password for the supplied email address
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function reset_password($email)
	{
		// generate random password
		$temp_password = \Str::random(8); // TODO - call method instead

		// update user's password with randomly generated one
		$user = self::where_email($email)->first();
		
		if ( $user ) {
			$user->password = $temp_password;
			$user->save();
            return $temp_password;
        }

        return false;
    }

    public function city(){

        return  $this->belongs_to('City');
    }

    public function city_name()
    {
        if(\Laravel\Cache::has('city'.$this->city_id))
        {
            $name = \Laravel\Cache::get('city'.$this->city_id);
        }
        else
        {
            if(!empty($this->city)) $name = $this->city->name;
            else $name = "---";
            \Laravel\Cache::forever('city'.$this->city_id, $name);
        }
        return $name;
    }

    public function gender_text()
    {
        switch ($this->gender) {
            case '1':
                return 'Bayan';
                break;

            case '2':
                return 'Bay';
                break;
            
            default:
                return '-';
                break;
        }
    }

}
