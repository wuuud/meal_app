<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
}
