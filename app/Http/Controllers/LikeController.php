<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // only()の引数内のメソッドはログイン時のみ有効
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
    }

    /**
     * 引数のIDに紐づくリプライにLIKEする
     *
     * @param $id リプライID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like($id)
    {
        Like::create([
            'like_id' => $id,
            'user_id' => Auth::id(),
        ]);

        session()->flash('success', 'お気に入りに登録しました！');

        return redirect()->back();
    }

    /**
   * 引数のIDに紐づくリプライにUNLIKEする
   *
   * @param $id リプライID
   * @return \Illuminate\Http\RedirectResponse
   */
    public function unlike($id)
    {
    $like = Like::where('meal_id', $id)->where('user_id', Auth::id())->first();
    $like->delete();

    session()->flash('success', 'お気に入りを解除しました');

    return redirect()->back();
    }
}
