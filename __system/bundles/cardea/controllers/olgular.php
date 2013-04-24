<?php

class Admin_Olgular_Controller extends AdminBase_Controller
{
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_index()
	{
		$olgular = QA\Question::with(array('user'))->order_by('created_at','desc')->paginate(10);
		$data = array('olgular' => $olgular);
		return view('admin.olgular.list', $data);
	}

	public function get_unapproved()
	{
		$unapproved = QA\Question::with(array('user'))->where('is_approved','=','0')->order_by('id', 'asc')->paginate(10);
		$data = array('olgular' => $unapproved);
		return view('admin.olgular.list', $data);
	}
}