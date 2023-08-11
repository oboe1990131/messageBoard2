<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\Authenticatable;

class LoginController extends Controller
{
// ---------------------------------------------------------------------------------------------------------- 
// 處理傳送登入頁麵給使用者
// ---------------------------------------------------------------------------------------------------------- 
    public function login(Request $request)
	{
        return view('messageboards.Login');
	}

// ---------------------------------------------------------------------------------------------------------- 
// 處理前端回傳之登入頁面那邊丟過來的請求，來處理登入訊息
// ---------------------------------------------------------------------------------------------------------- 
    public function toLogin( Request $request )
    {
/*----------------驗證帳密輸入格式---------------------------------------------------------*/
        $validator = $this->validateTologin($request);

        if($validator->fails()){
            return redirect('/login')->withErrors($validator->errors())->withInput();
        }

/*----------------認證帳密、存放session-----------------------------------------------------*/
        $check_data = [
            'useraccount' => $request->input('useraccount'),
            'password' => $request->input('password'),
        ];
        // TODO session 這邊要改
        if(Auth::attempt($check_data)){
            /**
             * 把登入資訊放進session裡面
             */
            $user_session_data = Auth()->user()->toArray();
            Session::put('user', $user_session_data);
            Session::put('member_id', $user_session_data['member_id']);
            Session::put('nickname', $user_session_data['nickname']);
            Session::put('useraccount', $user_session_data['useraccount']);

            return redirect()->route('messageboard.index');
        }
        else{
            return redirect('/login')->withErrors('您尚未註冊')->withInput();
        }
    }


    
// ---------------------------------------------------------------------------------------------------------- 
// 處理登出
// ---------------------------------------------------------------------------------------------------------- 
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return redirect()->route('login');
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ---------------------------------------------------------------------------------------------------------- 
    private function validateTologin(Request $request)
    {
        $parameters = $request->only('useraccount', 'password');

        $rules = [
            'useraccount' => 'required|alpha_dash',
            'password' => 'required|regex:/[0-9a-zA-Z]{8}/',
        ];
        $messages = [
            'useraccount.required' => '帳號為必填',
            'useraccount.alpha_dash' => '我們只支援少數符號',
            'password.required' => '密碼為必填',
            'password.regex' => '請輸入至少8碼的英文或數字',
        ]; 

        $validator = Validator::make($parameters, $rules, $messages);

        return $validator;
    }
}