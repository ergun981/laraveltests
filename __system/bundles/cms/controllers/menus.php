<?php

class Cms_Menus_Controller extends CmsBase_Controller {

	public function get_index() {
		
		$data = array(
			);
		return view('cms::menus.all', $data);
	}

	public function post_new() {
        
        $form = Input::all();
        $errors = Menu::validate($form);

        if (!$errors) {
        	$menu = new Menu;

        	$menu->name = $form['name'];
        	$menu->title = $form['title'];
        	$menu->order = $form['order'];

        	$menu->save();
            Cache::put('menus',Menu::all(),10);

        	Session::flash('status_success', 'Added menu #' . $menu->id);

        	return Redirect::back();
        } else {
        	return Redirect::back()
        	->with_errors($errors)
        	->with_input();
        }
    }

    public function get_delete($id) {
        $menu = Menu::find($id);

        if (!is_null($menu)) {
            $menu->delete();
            Cache::put('menus',Menu::all(),10);
            return true;
        }
        return false;
    }

    public function post_update()
    {
    	$posted = Input::all();
    	foreach ($posted['menu'] as $id => $val) {
    		$menu = Menu::find($id);
    		$menu->title = $val['title'];
    		$menu->name = $val['name'];
    		$menu->order = $val['order'];
    		$menu->save();
            Cache::put('menus',Menu::all(),10);
    	}
    	Session::flash('status_success', 'Menüler başarıyla güncellendi.');
    	return Redirect::back();
    }


}