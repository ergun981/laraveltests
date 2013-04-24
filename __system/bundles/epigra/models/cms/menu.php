<?php
namespace CMS;
use Eloquent;

class Menu extends Eloquent {


    public static $table = 'cms_menus';
    public static $timestamps = false;

    
    public static $rules = array(
        'name' => array(
            'required'
            , 'max:30'
            ,'unique:cms_menus,name'),
        'title' => array(
            'required'
            , 'max:30'
            ,'unique:cms_menus,title'),
        'order' => array(
            'integer'),
    );

    
    public function pages() { return $this->has_many('CMS\Page')->order_by('order', 'asc'); }

    public static function validate($form) {
        $validation = \Laravel\Validator::make($form, static::$rules);

        if ($validation->valid()) {
            return false;
        } else {
            return $validation->errors;
        }
    }

}