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

    public function image_url()
    {
        return Storage::url('images/meals/' . $this->image);
    }
    // titleやbody、imageは$post->titleのように、括弧がなくても呼び出すことが可能です。
    // image_urlも同じようにするには、モデルにアクセサを定義
    public function getImageUrlAttribute()
    {
        return Storage::url('images/meals/' . $this->image);
    }
}
