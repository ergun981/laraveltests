<?php
namespace CMS;
use Eloquent;

class Language extends Eloquent {


    public static $table = 'cms_languages';
    public static $timestamps = false;

    public static $rules = array(
        'lang' => array(
            'required'
            , 'max:2'
            ,'unique:cms_languages,lang'),
        'name' => array(
            'required'
            , 'max:30'
            ,'unique:langs,name'),
        'flag' => array(
            'required'
            , 'max:5'
            ,'unique:cms_languages,flag'),
        'is_online' => array(
            'in:0,1'
            ),
        'is_default' => array(
            'in:0,1'
            ),
        'order' => array(
            'integer'),
        );


    public function page_langs() { return $this->has_many('CMS\Page_Lang', 'lang_id'); }
    public function article_langs() { return $this->has_many('CMS\Article_Lang', 'lang_id'); }

    public static function validate($form) {
        $validation = \Laravel\Validator::make($form, static::$rules);

        if ($validation->valid()) {
            return false;
        } else {
            return $validation->errors;
        }
    }

}