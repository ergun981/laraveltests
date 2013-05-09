<?php
class Page_Lang extends Eloquent {

    public static $table = 'cms_page_langs';
    public static $timestamps = false;

   
    public static $rules = array(
        'lang_id' => array(
            'required', 'integer'),
        'page_id' => array(
            'required', 'integer'),
        'url' => array( 'unique:cms_page_langs,url'
        ),
        'link' => array(
            'alpha_dash',
            'unique:cms_page_langs,link'),
        'title' => array(
            'alpha_dash',
            'unique:cms_page_langs,title'
            , 'max:100'),
        'subtitle' => array(
            'alpha_dash',
        ),
        'nav_title' => array(
        ),
        'meta_title' => array(
        ),
        'meta_keyword' => array(),
        'meta_description' => array(),
        'is_online' => array(
            'in:0,1'
        ),
    );


    public function page() { return $this->belongs_to('Page'); }
    public function lang() { return $this->belongs_to('Language'); }

    public static function validate($form) {
        $validation = \Laravel\Validator::make($form, static::$rules);

        if ($validation->valid()) {
            return false;
        } else {
            return $validation->errors;
        }
    }

}