<?php
class Page extends Eloquent {

    public static $table = 'cms_pages';
    public static $timestamps = true;
    public static $rules = array(
        'menu_id' => array(
            'integer'),
        'parent_id' => array(
            'required',
            'integer'),
        'name' => array(
            'required',
            'max:30',
            'unique:cms_pages,name'),
        'author_id' => array(
            'integer'),
        'updater_id' => array(
            'integer'),
        'is_online' => array(
            'in:0,1'),
        'order' => array(
            'integer'),
        'publish_on' => array(),
        'publish_off' => array(),
        'link' => array('max:100', 'alpha_dash'),
        'link_type' => array(),
        'link_id' => array(),
    );

    public function menu() {
        return $this->belongs_to('Menu');
    }

    public function page_langs() {
        return $this->has_many('Page_Lang');
    }

    public function childs() {
        return $this->has_many('Page', 'parent_id');
    }

    public function parent() {
        return $this->belongs_to('Page');
    }

    public function page_articles() {
        return $this->has_many('Page_Article');
    }

    public static function validate($form) {
        $validation = \Laravel\Validator::make($form, static::$rules);

        if ($validation->valid()) {
            return false;
        } else {
            return $validation->errors;
        }
    }

}