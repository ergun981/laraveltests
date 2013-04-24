<?php
namespace QA;
use Eloquent;

class Answer extends Eloquent {

    /**
    * The name of the table associated with the model.
    *
    * @var string
    */
    public static $table = 'qa_answers';

    /**
    * Indicates if the model has update and creation timestamps.
    *
    * @var bool
    */
    public static $timestamps = true;

    /**
    * Validation kurallarını tutar.
    * @var array
    */
    public static $rules = array(
        'content' => array(
            'required'),
        );

    /**
     * Validate fuction
     * @param array
     * @return mixed
     */
    public static function validate($form) {
        $validation = \Laravel\Validator::make($form, static::$rules);

        if($validation->valid())
        {
            return false;
        }
        else {
            return $validation->errors;
        }
    }

    public function user(){
        return $this->belongs_to('User\User');
    }
    public function question() {
        return $this->belongs_to('QA\Question');
    }
    public function comments() {
        return $this->has_many('QA\Comment', 'to_id');
    }
    public function delete_all()
    {
        $this->comments()->delete();
        $this->delete();
    }
}

?>