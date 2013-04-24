<?php

class Admin_Users_Controller Extends AdminBase_Controller
{
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_index()
	{
		Asset::container('header')->add('beoro-datatable-css','assets/admin/js/lib/datatables/css/datatables_beoro.css');
		Asset::container('header')->add('beoro-tabletools-css','assets/admin/js/lib/datatables/extras/TableTools/media/css/TableTools.css');

		Asset::container('footer')->add('beoro-datatable-js1','assets/admin/js/lib/datatables/js/jquery.dataTables.min.js');
		Asset::container('footer')->add('beoro-datatable-js2','assets/admin/js/lib/datatables/extras/ColReorder/media/js/ColReorder.min.js');
		Asset::container('footer')->add('beoro-datatable-js3','assets/admin/js/lib/datatables/extras/ColVis/media/js/ColVis.min.js');
		Asset::container('footer')->add('beoro-datatable-js4','assets/admin/js/lib/datatables/extras/TableTools/media/js/TableTools.min.js');
		Asset::container('footer')->add('beoro-datatable-js5','assets/admin/js/lib/datatables/extras/TableTools/media/js/ZeroClipboard.js');
		Asset::container('footer')->add('beoro-datatable-js6','assets/admin/js/lib/datatables/js/jquery.dataTables.bootstrap.min.js');
		//tüm kullanıcıları çekip view içinde data tablea vereceğiz
		$users = User::all();
		$data = array(
			'users' => $users
			);
		return view('admin.users.list',$data);
	}

	public function get_detail($id)
	{
		Asset::container('footer')->add('jasny-input','assets/zoneportal/js/bootstrap-inputmask.js','jQuery');
		Asset::container('footer')->add('jasny-file','assets/zoneportal/js/bootstrap-fileupload.js','jQuery');
		//$id 'si belirtilen kullanıcının detay sayfası ve düzenleme formu gözükecek
		$cities = array('-');
		$get_cities = City::all();
		foreach ($get_cities as $city) {
			$cities[$city->id] = $city->name;
		}
		$data = array(
			'user' => User::find($id),
			'cities' => $cities,
			);
		return view('admin.users.detail', $data);
	}

	public function post_detail($id)
	{
		//kullanıcı detay sayfasından gelen formu işleyip güncelleme yapılacak
	}
}

?>