<?php
namespace QA;
use Eloquent;
use User;
class Question extends Eloquent {

    /**
    * The name of the table associated with the model.
    *
    * @var string
    */
    public static $table = 'qa_questions';

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
        'title' => array(
         'required'
         ),
        'content' => array(
            'required'),
        'image' => array(
         'required', 'mimes:jpg,gif,png,bmp', 'image'
         ),
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
    public function answers() {
        return $this->has_many('QA\Answer', 'question_id');
    }

    public function tags() {
        return $this->has_many('QA\Question_Tag', 'question_id');
    }

    public function delete_all()
    {
        //activityler siliniyor... @todo: action 13 ve  24 için puan veriliyor(question_id -> to_id)... silinecekse değerlendirilmeli....
        \Activity::where_in('action_id', array('12','26'))->where_content($this->id)->or_where_in('action_id', array('18', '13', '24'))->where_to_id($this->id)->delete();
        $answers = $this->answers()->get();
        foreach ($answers as $answer) {
            $answer->delete_all();
        }
        $this->delete();
    }

    public function approve()
    {
        $this->is_approved = 1;
        $this->save();
    }

    public function unapprove()
    {
        $this->is_approved = 0;
        $this->save();
    }

}