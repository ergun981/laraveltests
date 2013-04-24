<?php
namespace Forum;
use Eloquent;

class Post extends Eloquent{


	public static $table = 'forum_posts';
	public static $timestamps = true;
	private static $rules = array(
		'text' => 'required|min:10');

	public function category(){
		return $this->belongs_to('Forum\Category');
	}

	public function topic(){
		return $this->belongs_to('Forum\Topic');
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

	public function delete_post(){
		if($this->subject != $this->topic->subject){
			\Activity::where_in('action_id', array('15'))->where_content($this->id)->delete();		
			if($this->category->last_post_id == $this->id) {			
				$cat = $this->category;
				$last_post = \Forum\Post::where('id', '<>', $this->id)->where_category_id($cat->id)->order_by('created_at', 'desc')->first();
				$cat->last_post_id = $last_post->id;
				$cat->last_poster_id = $last_post->poster_id;
				$cat->last_post_time = $last_post->created_at;
				$cat->posts--;
				$cat->save();
			}
			if($this->topic->last_post_id == $this->id) {			
				$topic = $this->topic;
				$last_post = \Forum\Post::where('id', '<>', $this->id)->where_topic_id($topic->id)->order_by('created_at', 'desc')->first();
				$topic->last_post_id = $last_post->id;
				$topic->last_poster_id = $last_post->poster_id;
				$topic->last_post_time = $last_post->created_at;
				$topic->replies--;
				$topic->save();
			}
			$this->delete();
			return true;
		}
		return false;
	}

}
?>