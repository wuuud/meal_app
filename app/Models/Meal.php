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
        return $this->hasMany(like::class, 'meal_id');
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
    $likers = array();
    foreach($this->likes as $like) {
    array_push($likers, $like->user_id);
    }

    if (in_array($id, $likers)) {
        // true:Likeがついてる
        return true;
    } else {
        // false:Likeがついてない
        return false;
    }
}
}
