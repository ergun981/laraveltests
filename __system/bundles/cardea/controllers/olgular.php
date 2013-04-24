<?php

class Olgular_Controller Extends Base_Controller
{
	public $restful = true;

    public function __construct()
    {
        parent::__construct();
        $this->filter('before','auth');
    }

	public function get_index($page = 1)
    {
        Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
        setFancyAssets();
        $olgular = QA\Question::with(array('user', 'answers'=>function($q){$q->order_by('is_correct','desc');}, 'answers.user', 'answers.comments', 'answers.comments.user'))->where('is_approved','=','1')->order_by('created_at','desc')->paginate(10);
        $userFavorites = array();
        $favActions = Activity::where('user_id','=',Auth::user()->id)->where('action_id','=','18')->get('content');
        foreach ($favActions as $fav) {
            $userFavorites[$fav->content] = $fav->content;
        }
        $active_menu = 'index';
        $data = array('olgular' => $olgular,'favorites' => $userFavorites, 'active_menu' => $active_menu);
        return View::make('zoneportal.olgu.olgu', $data);
    }

    public function get_detail($id)
    {
        Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
        setFancyAssets();
        $olgular = QA\Question::where('is_approved','=','1')->where('id','=',$id)->order_by('created_at','desc')->paginate(10);
        $userFavorites = array();
        $favActions = Activity::where('user_id','=',Auth::user()->id)->where('action_id','=','18')->get('content');
        foreach ($favActions as $fav) {
            $userFavorites[$fav->content] = $fav->content;
        }
        $active_menu = 'index';
        $data = array('olgular' => $olgular,'favorites' => $userFavorites, 'active_menu' => $active_menu);
        return View::make('zoneportal.olgu.olgu', $data);
    }

    public function get_favorites()
    {
        Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
        setFancyAssets();
        $userFavorites = array('0');
        $favActions = Activity::where('user_id','=',Auth::user()->id)->where('action_id','=','18')->get('content');
        foreach ($favActions as $fav) {
            $userFavorites[$fav->content] = $fav->content;
        }
        $olgular = QA\Question::where('is_approved','=','1')->order_by('created_at','desc')->where_in('id',$userFavorites)->paginate(10);
        $active_menu = 'favorites';
        $data = array('olgular' => $olgular,'favorites' => $userFavorites, 'active_menu' => $active_menu);
        return View::make('zoneportal.olgu.olgu', $data);
    }

	/**
    * Show the form to create a new Question.
    *
    * @return void
    */
    public function get_create() // yaratmak Allah(cc)'a mahsustur
    {
    	Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
        return View::make('zoneportal.olgu.create');
    }

    /**
    * Create a new question.
    *
    * @return Response
    */
    public function post_create()
    {
        /**
         * Get all input
         * @var array
         */
        $form = Input::all();
        /**
         * Validate all form data
         * @var mixed
         */
        $errors = QA\Question::validate($form);
        if(! $errors)
        {
            $image = Str::random(32).'.'.File::extension(Input::file('image.name'));
            $imageUpload = Input::upload('image', path('public').'/images/questions', $image);
            $images = "{ 'path': [";
            //@todo: coklu resim ekleme işlemi için farklı bir yol bulunabilir!
            for($i=2; $i<10; $i++) {
                if(isset($form['images_'.$i])) {
                    $image_name = Crypter::encrypt(Input::get('images_'.$i));
                    $image_ext = File::extension(Input::get('images_'.$i));
                    $images .= "{\n";
                    $images .= Input::upload('images_'.$i, path('public').'/images/questions',$image_name.'.'.$image_ext);
                    $images .= "\n}\n";
                }
            }
            $images .="] }" ;
            $question = new QA\Question;
            $question->user_id = Auth::user()->id;
            $question->title = $form['title'];
            $question->content = $form['content'];
            $question->image = $image;
            $question->images = $images;
            $question->is_approved = 0;
            $question->save();
            save_log($question->user_id, 'olgu-share', $question->id, NULL, NULL);
            Session::flash('status_success', 'Olgu eklendi. Yönetici onayının ardından yayınlanacak.');

            return Redirect::to('olgular');
        }

        else
        {
            return Redirect::to('olgular/create')
            ->with_errors($errors)
            ->with_input();
        }
    }

    /**
    * View a specific question.
    *
    * @param  int   $id
    * @return void
    */
    public function get_view($id)
    {
        $question = QA\Question::with('answers', 'tags')->find($id);

        if(is_null($question))
        {
            return Redirect::to('olgular');
        }

        $this->layout->title   = 'Olgu #'.$id;
        $this->layout->content = View::make('questions.view',array('language' => Laravel\Config::get('application.language')))->with('question', $question);
    }

    /**
    * Show the form to edit a specific question.
    *
    * @param  int   $id
    * @return void
    */
    public function get_edit($id)
    {
        $question = QA\Question::find($id);

        if(is_null($question))
        {
            return Redirect::to('olgular');
        }

        $data  = array('title' => 'Olgu Düzenle',
                'question' => $question);
        return View::make('zoneportal.olgu.edit');
    }

    /**
    * Edit a specific question.
    *
    * @param  int       $id
    * @return Response
    */
    public function post_edit($id)
    {
        /**
         * Get all input
         * @var array
         */
        $form = Input::all();
        /**
         * Validate all form data
         * @var mixed
         */
        $errors = QA\Question::validate($form);

        if(! $errors)
        {
            $image_name = Crypter::encrypt(Input::get('image'));
            $image_ext = File::extension(Input::get('image'));
            $image = Input::upload('image', 'public/images/questions', $image_name.'.'.$image_ext);

            $question = QA\Question::find($id);

            if(is_null($question))
            {
                return Redirect::to('olgular');
            }

            $question->user_id = Auth::user()->id;
            $question->title = Input::get('title');
            $question->content = Input::get('content');
            $question->image = $image;

            $question->save();

            Session::flash('status_success', $question->id.' başlıklı olgu düzenlendi.');

            return Redirect::to('olgular');
        }

        else
        {
            return Redirect::to('questions/edit/'.$id)
            ->with_errors($errors)
            ->with_input();
        }
    }

    /**
    * Delete a specific question.
    *
    * @param  int       $id
    * @return Response
    */
    public function get_delete($id)
    {
        $question = QA\Question::find($id);

        if( ! is_null($question))
        {
            $question->delete_all();

            return true;
        }

        return false;
    }

    /**
    * Show the form to answer a specific question.
    *
    * @param  int   $id
    * @return void
    */
    public function get_answer($id)
    {
        $question = QA\Question::find($id);

        if(is_null($question))
        {
            return Redirect::to('olgular');
        }
        $data = array('question' => $question);
        return View::make('zoneportal.olgu.answermodal', $data);
    }

    /**
    * Edit a specific question.
    *
    * @param  int       $id
    * @return Response
    */
    public function post_answer($id)
    {

        /**
         * Get all input
         * @var array
         */
        $form = Input::all();
        /**
         * Validate all form data
         * @var mixed
         */
        $errors = QA\Answer::validate($form);

        if(! $errors)
        {
            $answer = new QA\Answer;
            $question = QA\Question::find($id);
            if(is_null($question))
            {
                return Redirect::to('olgular');
            }
            $question->is_replied = "1";
            $question->save();
            $answer->question_id = $id;
            $answer->user_id = Auth::user()->id;
            $answer->content = Input::get('content');
            $answer->save();
            action_point(save_log($answer->user_id, 'olgu-reply', $answer->id , $question->user_id, $question->id));
            Session::flash('status_success', $question->title.' başlıklı olgu için cevabınız eklendi.');

            return Redirect::to('olgular');
        }

        else
        {
            return Redirect::back()
            ->with_errors($errors)
            ->with_input();
        }
    }

    //cevap yorumlama
    public function post_comment($id)
    {
        $answer = QA\Answer::find($id);
        if(is_null($answer))
        {
            return Redirect::to('olgular');
        }

        $form = Input::all();

        $errors = QA\Comment::validate($form);

        if(!$errors)
        {
            $comment = new QA\Comment;
            $comment->to_id = $answer->id;
            $comment->user_id = Auth::user()->id;
            $comment->content = $form['content'];
            $comment->save();
            action_point(save_log($answer->user_id, 'olgu-answer-comment', $answer->id , $comment->comment, $comment->id));
            Session::flash('status_success', 'Yorumunuz eklendi.');
            return Redirect::to('olgular');
        }
        else
        {
            return Redirect::back()
            ->with_errors($errors)
            ->with_input();
        }
    }

    //Olguyu favorilere ekleme
    public function get_favorite($id){
        $question = QA\Question::find($id);
        if(is_null($question))
        {
            return "Böyle bir olgu bulunmuyor.";
        }

        $check = Activity::where('user_id','=',Auth::user()->id)->where('content','=',$id)->where('action_id','=','18')->first();
        if(!empty($check))
        {
            $check->delete();
            return '<i class="icon icon-star"></i> Favorilere Ekle';
        }
        else
        {
            save_log(Auth::user()->id, 'olgu-favorite', $id, NULL, NULL);
            return '<span style="color:#ff9b00;"><i class="icon icon-star"></i> Favorilere Eklendi</span>';
        }
    }

    //Olgu işlemleri raporlama
    public function get_report($id){
        $question = QA\Question::find($id);
        if(is_null($question))
        {
            return Redirect::to('olgular');
        }

        $question->is_reported = '1';
        $question->save();
        /**
         * @todo : yönetici mail bilgilendirmesi...
         */
        $mail = new Email;
        $mail->send_report($question->user_id, $question->id);
        Session::flash('status_success', $question->title.' başlıklı olgu raporlandı.');
        return Redirect::back();
    }

    //Olgu işlemleri kilitleme
    public function get_lock($question_id, $answer_id) {
        $question = QA\Question::find($question_id);
        if(is_null($question))
        {
            return Redirect::to('olgular');
        }
        $answer = QA\Answer::find($answer_id);
        $answer->is_correct = '1';
        $answer->save();
        $question->is_locked = '1';
        $question->save();
        action_point(save_log(Auth::user()->id, 'olgu-correct-answer', $answer->id, $answer->user_id, $question->id));
        Session::flash('status_success', $question->title.' başlıklı olgu için seçtiğiniz cevap onaylandı ve olgu kilitlendi.');
        return Redirect::back();
    }
    //Olgu işlemleri onaylama
    public function get_approve($id) {
        $question = QA\Question::find($id);
        if(is_null($question))  return Redirect::back();
        $question->approve();
        action_point(save_log(Auth::user()->id, 'olgu-approve', $question->id, $question->user_id, NULL));
        Session::flash('status_success', $question->title.' başlıklı olgu onaylandı.');
        return Redirect::back();
    }
    //Olgu işlemleri onay kaldırma
    public function get_unapprove($id) {
        $question = QA\Question::find($id);
        if(is_null($question))  return Redirect::back();
        $question->unapprove();
        Session::flash('status_success', $question->title.' başlıklı olgu onayı kaldırıldı.');
        return Redirect::back();
    }
    //Olgu işlemleri puanlama
    public function get_score($id) {
        $question = QA\Question::find($id);
        if(is_null($question))
        {
            return Redirect::to('olgular');
        }

        $question->score += 1;
        $question->save();
        Session::flash('status_success', $question->title.' başlıklı olgu için puanınız kaydedildi.');
        return Redirect::back();
    }
}