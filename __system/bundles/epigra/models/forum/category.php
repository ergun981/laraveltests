<?php
namespace Forum;
use Eloquent;

class Category extends Eloquent{

	
	public static $table = 'forum_categories';
	public static $timestamps = true;
	private static $rules = array(
		'parent_id' => 'required|integer',
		'title' => 'required|max:50',
		'description' => 'required',
		'order' => 'required|integer',
	);

	public $children;

	public function childs(){
		return $this->has_many('Forum\Category', 'parent_id');
	}

	public function topics(){
		return $this->has_many('Forum\Topic');
	}

	public function validate(){

	}

	public function delete_all()
	{
		foreach ($this->childs()->get() as $child) {
			$child->delete_all();
		}

		foreach ($this->topics()->get() as $topic) {
			$topic->delete_post();
		}

		return $this->delete();
	}
}
?>