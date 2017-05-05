<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserInputRequest;
use Auth;

class UsersController extends Controller
{

    public function __construct ()
    {
        $this->middleware ('auth', [
            'only' => ['edit', 'update', 'destroy', 'followings', 'followers']
        ]);
        $this->middleware('guest' , [
            'only' => ['create']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::paginate(8);

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('user.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserInputRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
        ]);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success' , '激活邮件已经发送到你的邮箱');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::findOrFail($id);
        $statuses = $user->statuses()
                            ->orderBy('updated_at', 'desc')
                            ->paginate(5);
        return view('user.show', compact ('user','statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        return view('user.edit', compact ('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UserUpdateInput $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return redirect()->back();
    }

    public function confirmEmail ($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    protected function sendEmailConfirmationTo ($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 laravel-china-test 应用！请确认你的邮箱。";
        /*此处的发送人是在laravel 的 config/mail.php中，其他设置在 .env*/
        \Mail::queue($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });

    }

    /*关注的人*/
    public function followings ($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followings()->paginate(8);
        $title = '关注的人';
        return view('user.show_follow', compact ('users', 'title'));
    }

    /*关注我的*/
    public function followers ($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followers()->paginate(8);
        $title = '关注的人';
        return view('user.show_follow', compact ('users', 'title'));
    }
}
