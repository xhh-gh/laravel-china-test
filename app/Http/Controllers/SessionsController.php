<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginInput;
use Auth;

class SessionsController extends Controller
{

    public function __construct ()
    {
        $this->middleware ('auth', [
            'only' => ['edit', 'update']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);

    }
    //
    public function create ()
    {
        return view('sessions.create');
    }

    public function store (UserLoginInput $request)
    {
        $user = [
            'email'    => $request->email,
            'password' => $request->password,
        ];
        if(Auth::attempt($user, $request->has('remember'))) {
            session()->flash('success' , '欢迎回来'.Auth::user()->name);
            return redirect()->intended(route('users.show',[Auth::user()]));
        } else {
            session()->flash('danger' , '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    public function destroy ()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect()->route('login');
    }
}