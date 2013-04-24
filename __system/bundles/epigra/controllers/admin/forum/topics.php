<?php

class Admin_Forum_Topics_Controller Extends AdminBase_Controller
{
	public function get_unapproved_list()
	{
		Asset::container('header')->add('beoro-datatable-css','assets/admin/js/lib/datatables/css/datatables_beoro.css');
		Asset::container('header')->add('beoro-tabletools-css','assets/admin/js/lib/datatables/extras/TableTools/media/css/TableTools.css');

		Asset::container('footer')->add('beoro-datatable-js1','assets/admin/js/lib/datatables/js/jquery.dataTables.min.js');
		Asset::container('footer')->add('beoro-datatable-js2','assets/admin/js/lib/datatables/extras/ColReorder/media/js/ColReorder.min.js');
		Asset::container('footer')->add('beoro-datatable-js3','assets/admin/js/lib/datatables/extras/ColVis/media/js/ColVis.min.js');
		Asset::container('footer')->add('beoro-datatable-js4','assets/admin/js/lib/datatables/extras/TableTools/media/js/TableTools.min.js');
		Asset::container('footer')->add('beoro-datatable-js5','assets/admin/js/lib/datatables/extras/TableTools/media/js/ZeroClipboard.js');
		Asset::container('footer')->add('beoro-datatable-js6','assets/admin/js/lib/datatables/js/jquery.dataTables.bootstrap.min.js');
		$unapproveds = Forum\Topic::where_is_approved('0')->get();
		$data = array(
			'topics' => $unapproveds
			);
		return View::make('admin.forum.list', $data);
	}

	public function get_approve($id) {
        $topic = Forum\Topic::find($id);
        if(is_null($topic))
        {
            return Redirect::to('admin.forum');
        }

        $topic->approve();

		$category = $topic->category;
		if($category->last_post_time < $topic->created_at){
			$category->last_post_id 		= $id;
			$category->last_poster_id 		= $topic->poster_id;
			$category->last_post_time 		= $topic->craeted_at;
			$category->topics 				= $category->topics+1;
			$category->posts 				= $category->posts+1;
			$category->last_topic_subject 	= $topic->subject;
			$category->save();
		}
		if($category->id = 1)
        	action_point(save_log(Auth::user()->id, 'forumTitle-approve', $topic->id, $topic->poster_id, NULL));
        else 
        	save_log(Auth::user()->id, 'forumTitle-approve', $topic->id, $topic->poster_id, NULL);
		Cache::forever('c_forum',Forum\Topic::where_is_approved('0')->count());
        Session::flash('status_success', $topic->subject.' başlıklı forum başlığı onaylandı.');
        return Redirect::back();
    }
}