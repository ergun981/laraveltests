<?php
namespace QA;
use Eloquent;

class Question_Tag extends Eloquent {

/**
* The name of the table associated with the model.
*
* @var string
*/
public static $table = 'qa_question_tags';

/**
* Indicates if the model has update and creation timestamps.
*
* @var bool
*/
public static $timestamps = false;

/**
* Validation kurallarını tutar.
* @var array
*/
public static $rules = array(
    'tag_id' => array(
        'required', 'integer'),
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

public function question() {
    return $this->belongs_to('QA\Question');
}
public function tag() {
    return $this->belongs_to('QA\Tag');
}
}