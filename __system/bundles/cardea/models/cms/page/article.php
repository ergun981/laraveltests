<?php
namespace CMS;
use Eloquent;
class Page_Article extends Eloquent {

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    public static $table = 'cms_page_articles';

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
        'article_id' => array(
            'required', 'integer'),
        'page_id' => array(
            'required', 'integer'),
        'order' => array('integer'),
        'link' => array(
            'unique:cms_page_articles,link'
            , 'max:100'),
        'link_type' => array(),
        'link_id' => array(),
        'is_online' => array(
            'in:0,1'
        ),
    );

    /**
     * Establish the relationship between a page_article and a admin page.
     *
     * @return Laravel\Database\Eloquent\Relationships\Belongs_To
     */
    public function page() {
        return $this->belongs_to('CMS\Page');
    }

    /**
     * Establish the relationship between a page_article and a admin article.
     *
     * @return Laravel\Database\Eloquent\Relationships\Belongs_To
     */
    public function article() {
        return $this->belongs_to('CMS\Article');
    }

    /**
     * Validate fuction
     * @param array
     * @return mixed
     */
    public static function validate($form) {
        $validation = Validator::make($form, static::$rules);

        if ($validation->valid()) {
            return false;
        } else {
            return $validation->errors;
        }
    }

}