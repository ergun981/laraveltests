<?php
class Article_Lang extends Eloquent {

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    public static $table = 'cms_article_langs';

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
        'lang_id' => array(
            'required', 'integer'),
        'article_id' => array(
            'required', 'integer'),
        'url' => array(           
            'alpha_dash',
            'unique:cms_article_langs,url'
        ),
        'title' => array(
             'max:100'),
        'subtitle' => array(
        ),
        'meta_title' => array(
        ),
        'summary' => array(),
        'content' => array(),
        'meta_keyword' => array(),
        'meta_description' => array(),
        'is_online' => array(
            'in:0,1'
        ),
    );

    /**
     * Establish the relationship between a article_lang and a admin article.
     *
     * @return Laravel\Database\Eloquent\Relationships\Belongs_To
     */
    public function article() {
        return $this->belongs_to('Article');
    }

    /**
     * Establish the relationship between a article_lang and a admin lang.
     *
     * @return Laravel\Database\Eloquent\Relationships\Belongs_To
     */
    public function lang() {
        return $this->belongs_to('Language');
    }

    /**
     * Validate fuction
     * @param array
     * @return mixed
     */
    public static function validate($form) {
        $validation = \Laravel\Validator::make($form, static::$rules);

        if ($validation->valid()) {
            return false;
        } else {
            return $validation->errors;
        }
    }

}