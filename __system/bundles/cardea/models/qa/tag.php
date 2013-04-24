<?php
namespace QA;
use Eloquent;

class Tag extends Eloquent {

/**
* The name of the table associated with the model.
*
* @var string
*/
public static $table = 'qa_tags';

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
    'name' => array(
        'required'),
    'description' => array(
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

public function question_tags() {
    return $this->has_many('QA\Question_Tag');
}
}

?>