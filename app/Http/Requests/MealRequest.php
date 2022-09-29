<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $route = $this->route()->getName();

        $rule = [
            'title' => 'required|string|max:50',
            'body' => 'required|string|max:200',
            'list' => 'required',
            
            // edit時は不要のため
            // 'image' => 'required|file|image|mimes:jpg,png',
        ];
        // 登録時か更新時で且つ画像が指定された時だけ、
        // imageのバリデーションを設定。
        if (
            $route === 'meals.store' ||
            ($route === 'meals.update' && $this->file('image'))
        ) {
            $rule['image'] = 'required|file|image|mimes:jpg,png';
        }

        return $rule;
    }
}
