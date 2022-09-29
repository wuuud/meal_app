<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'category_id',
        
    ];

    public function user()
    {
        return $this->belongsto(User::class);
    }

    public function likes()
    {
        return $this->hasMany(like::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        // 削除するStorage::delete('images/posts/' . $post->image)の'images/posts/'のimage_urlの内容と重複する部分があるため、
        // アクセサを追加して重複する部分をまとめる
        //return Storage::url('images/meals/' . $this->image);
        return Storage::url($this->image_path);
    }
    // titleやbody、imageは$post->titleのように、括弧がなくても呼び出すことが可能です。
    // image_urlも同じようにするには、モデルにアクセサを定義
    public function getImagePathAttribute()
    {
        return 'images/meals/' . $this->image;
    }


    // お気に入り
    /**
    * リプライにLIKEを付いているかの判定
    *
    * @return bool true:Likeがついてる false:Likeがついてない
    */
    public function is_liked_by_auth_user()
    {
    $id = Auth::id();
    // 要素が0個の配列を作成 https://www.tohoho-web.com/js/array.htm#newArray
    $likers = array();
    foreach($this->likes as $like) {
    // array_pushを使って配列の最後に1つ以上の要素を追加。プログラム途中で要素数が可変する際などに使用。 
    // https://magazine.techacademy.jp/magazine/26812
    // array_push(追加先の配列,追加する値1,追加する値2・・・)
    // 配列likersに、$like->user_idを追加
    array_push($likers, $like->user_id);
    }
    // 配列likerの中に要素id が含まれれば
    // in_array https://qiita.com/ritukiii/items/3a6add378ae089ab5d70
    // 第三引数のstrictはデフォルトでfalseになっているため、型比較までしない。
    // in_array使うときは黙って第三引数にtrueを指定しなさい
    if (in_array($id, $likers, true)) {
        // true:Likeがついてる
        return true;
    } else {
        // false:Likeがついてない
        return false;
    }
}
}
