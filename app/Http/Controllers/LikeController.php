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
    public function like(Request $request, $id)
    {
        $like = new Like();
        // 値の用意    ログイン済みのユーザーhttps://de-vraag.comja66088510
        $like->meal_id = $request->id;
        $like->user_id = Auth::user()->id;
        
        $like->save();

        return redirect()
            ->route('meals.show', $id)
            ->with('success', 'お気に入りに登録しました！');
    }

    /**
     * 引数のIDに紐づくリプライにUNLIKEする
     *
     * @param $id リプライID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlike($id)
    {
        // A and B
        // 'name'カラムが'名前1'でかつ'name'カラムが'名前2'のレコードを取得
        // https://qiita.com/ktanoooo/items/64bd5d515e45224f6b95
        $like = Like::where('meal_id', $id)
                    ->where('user_id', Auth::user()->id)
            //first()は結果データの最初の1件のみを取得します。結果が何件であっても、1件のみを取得します。https://www.ritolab.com/entry/93#aj_3_2
                    ->first();
        
        try {
            $like->delete();
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
        return redirect()
            ->route('meals.show', $id)
            ->with('success', 'お気に入りを削除しました');
    }
}
