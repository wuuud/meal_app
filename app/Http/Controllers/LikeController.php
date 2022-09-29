<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Meal;

class LikeController extends Controller
{
    // コントローラーに記載
    // https://reffect.co.jp/laravel/laravel-gate-policy-understand#can
    // コントローラーの__constructメソッドにauthorizeResourceメソッドを
    // 追加するとコントローラーの個別のメソッドでauthorizeメソッドを使わなくても
    // Policyファイルで指定したメソッドが有効になります。
    // only()の引数内のメソッドはログイン時のみ有効
    // public function __construct()
    // {
    //     // $this->middleware(['auth', 'verified'])
    //     $this->middleware('auth')->only(['like', 'unlike']);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request, Meal $meal, Like $like)
    {
        //なりすましを確認         updateの内容は＄like
        if ($request->user()->cannot('update', $like)) {
            return redirect()
                ->route('meals.index')
                ->withErrors('自分以外のお気に入りは更新できません');
        }
        // storeから変更
        $like->fill($request->all());
        // トランザクションなし
        try {
            $like->save();
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
        return redirect()
                ->route('meals.index')
                ->with('success', 'お気に入りに登録しました！');

        // https://katsusand.dev/posts/laravel-save-data-db/
        // Likeモデルクラスから create メソッドを呼ぶことで、
        // インスタンスの作成 → 属性の代入 → データの保存を一気通貫
        // Like::create([
        //         'meal_id' => $id,
        //         'user_id' => Auth::id(),
        // ]);

        // return redirect()
        //     ->route('meals.show', $id)
        //     ->with('id','success', 'お気に入りに登録しました！');
        // /**
        // * 引数のIDに紐づくリプライにLIKEする
        // *
        // * @param $id リプライID
        // * @return \Illuminate\Http\RedirectResponse

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function unlike(Request $request, Meal $meal, Like $like)
    {
       //上記はRequest $requestでバリデーションは
        //                   ’delete’でpolicyのdeleteに飛んでいってる
        //なりすましを確認             deleteの権限はありますか？
        // できない場合はtrueで実行
        if ($request->user()->cannot('delete', $like)) {
            return redirect()
            // @can('update', $meal) 
            // <a href="{{ route('meals.unlike', ['id' => $meal->id]) }}" 
                ->route('meals.index')
                ->withErrors('自分以外のお気に入りは解除できません');
        }

        // トランザクションなし
        try {
            $like->delete();
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
        return redirect()
                ->route('meals.index')
                ->with('success', 'お気に入りを削除しました');
    // whereメソッドを呼び出し条件を指定した上でfirstメソッドを呼ぶ
    // $like = Like::where('meal_id', $id)
    //             ->where('user_id' ,Auth::id())
    // //first()は1件だけ返ってくる https://zenn.dev/ytksato/articles/125d3c9c79c1b5
    //             ->first();

    // $like->delete();
    
    // return redirect()
    //         ->route('meals.show', $id)
    //         ->with('like', 'success', 'お気に入りを解除しました');

//     /**
//    * 引数のIDに紐づくリプライにUNLIKEする
//    *
//    * @param $id リプライID
//    * @return \Illuminate\Http\RedirectResponse
//    */
    }
}

