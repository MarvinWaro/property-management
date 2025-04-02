<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {

        if(Auth::id())
        {
            $usertype = Auth::user()->usertype;
            if($usertype == 'user')
            {
                return view('user-dashboard');
            } else if($usertype == 'admin')
            {
                return view('dashboard');
            }else
            {
                return redirect()->back();
            }
        }

    }
}
