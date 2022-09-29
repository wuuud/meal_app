<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Meal;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
        
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request, $id, Like $like)
    {
        // https://katsusand.dev/posts/laravel-save-data-db/
        // Likeモデルクラスから create メソッドを呼ぶことで、
        // インスタンスの作成 → 属性の代入 → データの保存を一気通貫
        Like::create([
                'meal_id' => $id,
                // https://de-vraag.com/ja/66088510
                'user_id' => Auth::user()->id,
        ]);

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

        // * 引数のIDに紐づくリプライにLIKEする
        // *
        // * @param $id リプライID
        // * @return \Illuminate\Http\RedirectResponse
    }

    /**
   * 引数のIDに紐づくリプライにUNLIKEする
   *
   * @param $id リプライID
   * @return \Illuminate\Http\RedirectResponse
   */
    public function unlike($id)
    {
        // whereメソッドを呼び出し条件を指定した上でfirstメソッドを呼ぶ
        $like = Like::where('meal_id', $id)
                ->where('user_id', Auth::user()->id)
        //first()は1件だけ返ってくる https://zenn.dev/ytksato/articles/125d3c9c79c1b5
                ->first();
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
    }
}

