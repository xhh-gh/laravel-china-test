<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserUpdateInput extends Request
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
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function messages ()
    {
        return [
            'name.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
            'password.min' => '请输入6位以上密码',
            'password.confirmed' => '密码和重复密码不一致',
        ];
    }
}
