<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // 配列内の要素を書き込み可能にする
    // テキスト コメント user_idとpost_idについては、Commentコントローラーで個別に代入するので、指定しません
    protected $fillable = [
        'meal_id', 
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
