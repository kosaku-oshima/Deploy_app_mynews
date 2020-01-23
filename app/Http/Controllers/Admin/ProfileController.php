<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;  //Validationのためにこの1行も追記

use App\profileHistory;
use Carbon\Carbon;


class ProfileController extends Controller
{

    public function add()
    {
        return view('admin.profile.create');
    }


//以下createアクションをValidationのために編集
//サンプルコードにはformから送られてきたimageに関する処理が記述されていたが、ここでは削除した。
    public function create(Request $request)          //()内に　Request $request　を記述。
    {

      $this->validate($request, Profile::$rules);     //サンプルコード中のNewsをProfileに変更。

      $profile = new Profile;                         //同じくProfileに変更。$newsは$profileに変更。
      $form = $request->all();

      

      // フォームから送信されてきた_tokenを削除する
      unset($form['_token']);

      // データベースに保存する
      $profile->fill($form);
      $profile->save();
//ここまでをValidationのために編集
        
        return redirect('admin/profile');
    }
    


    
      public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      if ($cond_title != '') {
          // 検索されたら検索結果を取得する
          $posts = Profile::where('title', $cond_title)->get();
      } else {
          // それ以外はすべてのニュースを取得する
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
  }


    
    
    

    public function edit(Request $request)
    {
        $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
        return view('admin.profile.edit',['profile_form' => $profile]);
    }

    public function update(Request $request)
    {        

      $this->validate($request, Profile::$rules);
      $profile = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profile_form = $request->all();
      
      unset($profile_form['_token']);
      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();
      
      $profile_history = new profileHistory;
      $profile_history -> profile_id = $profile->id;
      $profile_history -> edited_at = Carbon::now();

      $profile_history -> save();
        
      return redirect('admin/profile');
    }
    
    
    
  public function delete(Request $request)
  {
      $profile = Profile::find($request->id);
      // 削除する
      $profile->delete();
      return redirect('admin/profile');
  }  




}
