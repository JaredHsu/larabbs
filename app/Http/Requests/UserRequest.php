<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
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
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:jpg,png,git,jpeg|dimensions:min_width=208,min_height=208'
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => '图像必须是jpg,jpeg,png,gif格式',
            'avatar.dimensions' => '图片的清晰度不够，宽和高需要 208px 以上',
            'name.unique' => '用户名必须是唯一的',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须在3-25个字符之间',
            'name.required' => '用户名不能为空',
        ];
    }
}
