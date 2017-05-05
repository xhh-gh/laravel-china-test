<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class StatusesController extends Controller
{
    public function __construct ()
    {
        $this->middleware ('auth', [
            'only' => ['store', 'destroy']
        ]);
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'contents' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request->contents
        ]);

        return redirect()->back();
    }

    public function destroy ($id)
    {
        $status = Status::findOrFail($id);
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success','删除帖子成功！！');
        return redirect()->back();
    }
}

