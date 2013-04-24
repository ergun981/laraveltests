<?php
/**
 * User-Attemp Sınıfı
 * giriş isteklerini yönetir.
 */
namespace User;
use Eloquent;
class Attempt {

	private $login_id;
	private $ip_address;
	private $attempts;
	private $is_suspend = TRUE;
	private static $table_suspend = "user_suspended";
	private static $limit = array('time'=>5,'attempt'=>3);

	public function __construct($email = NULL)
	{
		$this->ip_address = \Laravel\Request::ip();
		$register = NULL; //user nesnesi
		if(!is_null($email)) $register =  User::where_email($email)->first();
        //kullanıcı sistemde kayıtlı mı, değil mi?
		if(!is_null($register)) $this->login_id = $register->id;
		else $this->login_id = -100; //kullanıcı kayıtlı değilse -100 olsun idsi...
        //login_id ve ip bilgilerinin suspend durumunu kontrol et
		$query = \Laravel\Database::table(static::$table_suspend);
		if ($this->login_id)
		{
			$query = $query->where('login_id', '=', $this->login_id);
		}

		if ($this->ip_address)
		{
			$query = $query->where('ip', '=', $this->ip_address);
		}
		$result = $query->get();

		foreach ($result as &$row)
		{
			$row = get_object_vars($row);

			$time = new \DateTime($row['last_attempt_at']);
			$time = $time->modify('+'.static::$limit['time'].' minutes')->getTimestamp();

            // bekleme zamanı bitmişse kaydedilen bilgileri temizle ve bekleme durumunu false yap  
			if ($row['unsuspend_at'] != '0000-00-00 00:00:00' and $row['unsuspend_at'] <= sql_timestamp())
			{
				$this->clear($row['login_id'], $row['ip']);
				$row['attempts'] = 0;
				$this->is_suspend = FALSE;
			}
			//bekleme zamanına girmemiş ve limit deneme sayısı geçilmemişse bekleme durumunu false yap 
			else if ($row['unsuspend_at'] == '0000-00-00 00:00:00' and $row['attempts'] <= static::$limit['attempt'])
			{
				$this->is_suspend = FALSE;
			}
			//limit geçilmişse bekleme durumunu true yap 
			else if ($row['attempts'] >= static::$limit['attempt'])
			{
				$this->is_suspend = TRUE;
			}
		}

		if (count($result) > 1)
		{
			$this->attempts = $result;
		}
		elseif ($result)
		{
			$this->attempts = $result[0]['attempts'];
		}
		else
		{
			$this->attempts = 0;
			$this->is_suspend = FALSE;
		}
	}
	/**
	 * Check Number of Login Attempts
	 *
	 * @return  int
	 */
	public function get()
	{
		return $this->attempts;
	}

	/**
	 * Gets attempt limit number
	 *
	 * @return  int
	 */
	public function get_limit()
	{
		return static::$limit['attempt'];
	}
	/**
	 * Check Suspend
	 *
	 * @return  int
	 */
	public function is_suspend()
	{
		return $this->is_suspend;
	}
	public function add() 
	{
		if (empty($this->login_id) or empty($this->ip_address))
		{
			return FALSE;
		}

        // this shouldn't happen, but put it just to make sure
		if (is_array($this->attempts))
		{
			return FALSE;
		}

		if ($this->attempts)
		{
			\Laravel\Database::table(static::$table_suspend)
			->where('login_id', '=', $this->login_id)
			->where('ip', '=', $this->ip_address)
			->update(array(
				'attempts' => ++$this->attempts,
				'last_attempt_at' => sql_timestamp(),
				));
		}
		else
		{
			\Laravel\Database::table(static::$table_suspend)
			->insert(array(
				'login_id' => $this->login_id,
				'ip' => $this->ip_address,
				'attempts' => ++$this->attempts,
				'last_attempt_at' => sql_timestamp(),
				));
		}        

	}
        /**
     * Suspend
     *
     * @param string
     * @param int
     */
        public function suspend()
        {
        	if (empty($this->login_id) or empty($this->ip_address))
        	{
        		return FALSE;
        	}

        	$unsuspend_at = new \DateTime(sql_timestamp());
        	$unsuspend_at->modify('+'.static::$limit['time'].' minutes');

        // only updates table if unsuspended at has no value
        	$result = \Laravel\Database::table(static::$table_suspend)
        	->where('login_id', '=', $this->login_id)
            ->where('ip', '=', $this->ip_address) //\Input::real_ip()
            ->where('unsuspend_at', '=', null)
            ->or_where('unsuspend_at', '=', 0)
            ->or_where('unsuspend_at','=','0000-00-00 00:00:00')
            ->update(array(
            	'suspended_at' => sql_timestamp(),
            	'unsuspend_at' => sql_timestamp($unsuspend_at->getTimestamp()),
            	));
            $this->is_suspend = TRUE;
        }

    /**
     * Clear Login Attempts
     *
     * @param string
     * @param string
     */
    public function clear()
    {
    	$query = \Laravel\Database::table(static::$table_suspend);

    	if ($this->login_id)
    	{
    		$query = $query->where('login_id', '=', $this->login_id);
    	}

    	if ($this->ip_address)
    	{
    		$query = $query->where('ip', '=', $this->ip_address);
    	}

    	$result = $query->delete();
    	$this->attempts = 0;
    	$this->is_suspend = FALSE;
    }
}