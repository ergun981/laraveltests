<?php
namespace Forum;
use Eloquent;

class Topic extends Eloquent{
	

	public static $table = 'forum_topics';
	public static $timestamps = true;

	private static $rules = array(
		'subject' => 'required|min:3',
		'text' => 'required|min:10');

	public function category(){
		return $this->belongs_to('Forum\Category');
	}

	public function posts(){
		return $this->has_many('Forum\Post');
	}

    public function poster(){
        return $this->belongs_to('User\User', 'poster_id');
    }
	public static function validate($form){
		$validation = \Laravel\Validator::make($form, static::$rules);

		if($validation->valid()){
			return false;
		}else{
			return $validation->errors;
		}
	}

	public function delete_topic()
	{
        \Activity::where_in('action_id', array('14','27'))->where_content($this->id)->or_where_in('action_id', array('15'))->where_to_id($this->id)->delete();
		if($this->last_post_id == $this->category->last_post_id){
			$last_post = \Forum\Post::where('topic_id', '<>', $this->id)->where_category_id($this->category->id)->order_by('created_at', 'desc')->first();
			$this->category->last_post_id=$last_post->id;
			$this->category->last_poster_id=$last_post->poster_id;
			$this->category->last_post_time=$last_post->created_at;
			$this->category->save();			
		}
		$this->posts()->delete();
		return $this->delete();
	}

	public function approve()
    {
        $this->is_approved = 1;
        $this->save();
    }
    
    public function unapprove()
    {
        $this->is_approved = 1;
        $this->save();
    }

}
?>