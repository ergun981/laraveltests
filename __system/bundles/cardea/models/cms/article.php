<?php
namespace CMS;
use Eloquent;
use User;
class Article extends Eloquent {

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    public static $table = 'cms_articles';

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
    //TODO:name -> required
    public static $rules = array(
        'name' => array(
             'max:140'
            , 'unique:cms_articles,name'),
        'author_id' => array('integer'),
        'updater_id' => array('integer'),
        'publish_on' => array(),
        'publish_off' => array(),
    );

    /**
     * Establish the relationship between a article and admin article langs.
     *
     * @return Laravel\Database\Eloquent\Relationships\Has_Many
     */
    public function article_langs() {
        return $this->has_many('CMS\Article_Lang');
    }

    /**
     * Establish the relationship between a article and admin page articles.
     *
     * @return Laravel\Database\Eloquent\Relationships\Has_Many
     */
    public function page_articles() {
        return $this->has_many('CMS\Page_Article');
    }
    public function author(){
        return $this->belongs_to('User', 'author_id');
    }

    public function author_name(){
        return $this->author->full_name();
    }

    public function delete_all()
    {
        $this->article_langs()->delete();
        $this->page_articles()->delete();
        return $this->delete();
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