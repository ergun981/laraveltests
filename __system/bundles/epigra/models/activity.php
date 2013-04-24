<?
class Activity extends Eloquent {
    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    public static $table = 'activity_log';

    /**
     * Indicates if the model has update and creation timestamps.
     *
     * @var bool
     */
    public static $timestamps = false;

    public function action()
    {
        return $this->belongs_to('Action');
    }
    public function action_title(){ //action tablosu descriptionlar eklenecek...
        return $this->action->description;
    }
    /**
     * For user_id
     */
    public function user(){
        return $this->belongs_to('User\User');
    }

    /**
     * For to_user_id
     */
     public function to_user(){
        return $this->belongs_to('User\User', 'to_user_id');
    }
}