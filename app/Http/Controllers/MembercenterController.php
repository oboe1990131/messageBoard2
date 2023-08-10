<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Usersession;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MembercenterController extends Controller
{
// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function index()
    {
        $useraccount =  Usersession::get()['useraccount'];
        $member_id = Usersession::get()['member_id'];
        $nickname = Usersession::get()['nickname'];

        return view('messageboards.membercenter', compact('useraccount', 'member_id', 'nickname'));
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function create()
    {
        return view('messageboards.regist');
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {
/*----------------驗證格式-----------------------------------------------------*/
        $validator = $this->validateCreate();
        
        if($validator->fails()){
            return redirect('/regist')->withErrors($validator);
        }
/*----------------驗證帳號、暱稱是否重複使用-------------------------------------*/
        $is_reuse = Member::checkReUse($request);

        if($is_reuse){
            return redirect()
                    ->back()
                    ->withErrors('重複使用')
                    ->withInput();
        }
/*----------------驗證通過，新增資料---------------------------------------------*/
        $new_member = new Member();
        $new_member->useraccount = $request->input('useraccount');
        // TODO for原生雜湊方式
        // $password = hash('sha256', $password);
        $new_member->password = Hash::make($request->input('password'));
        $new_member->nickname = $request->input('nickname');
        $new_member->save();
        return view('messageboards.login');
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function update(Request $request, Member $membercenter)
    {
/*---------驗證格式正確性 ( 依照使用者要更改的項目，決定我的判斷標準，並做判斷 ) --------*/
        $validator = $this->validateUpdate($request);

        if($validator->fails()){
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }
/*----------------驗證是否重複使用--------------------------------------------------*/
        $is_reuse = Member::checkReUse($request);

        if($is_reuse){
            return redirect()
                    ->back()
                    ->withErrors('重複使用')
                    ->withInput();
        }

        $membercenter->member_id = Usersession::get()['member_id'];
        $membercenter->nickname = $request->input('nickname');

        if($request->has('password') && ($request->input('password') != "")){
            $membercenter->password = Hash::make($request->input('password'));
        }

        $membercenter->save();
/*---------------資料更新好之後，也更新session---------------------------------------*/
        // TODO session修改
        $user_session_data = Auth::user()->toArray();
        Session::put('user', $user_session_data);
        Session::put('nickname', $user_session_data['nickname']);

        // 幫我把true值存進session的success這個key當中，前端會依此訊號來決定要不要跳出更新成功視窗
        session()->flash('success', true);

        return redirect()->route('messageboard.index');
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    private function validateCreate()
    {
        $parameters = request()->only('useraccount', 'password', 'passwordoublechk', 'nickname');
        $rules = [
            'useraccount' => 'required|max:50',
            'password' => 'required|min:8',
            // TODO 複檢：密碼至少7碼起跳，比較安全，下面會員更新那邊也是這樣設置
            'passwordoublechk' => 'required|same:password',
            'nickname' => 'required||max:10',
        ];
        $messages = [
            'useraccount.required' => '帳號欄位必填',
            'useraccount.max' => '帳號不超過50字',
            'password.required' => '密碼欄位必填',
            'password.min' => '密碼至少5字',
            'passwordoublechk.required' => '密碼確認欄位必填',
            'passwordoublechk.same' => '密碼兩次填寫不同',
            'nickname.required' => '暱稱欄位必填',
            'nickname.max' => '暱稱最多取名10個字以內',
        ];
        $validator = Validator::make($parameters, $rules, $messages);
        
        return $validator;
    }

// ----------------------------------------------------------------------------------------------------------
// 依照使用者要更改的項目，決定我的判斷標準，並進行驗證
// ----------------------------------------------------------------------------------------------------------
    private function validateUpdate($request)// TODO 這邊其實也可以用request()->all()，這樣以後比較不用怕資料多增加
    {
        $parameters = $request->only('password', 'passwordoublechk', 'nickname');
        $rules = [
            'password' => 'nullable|min:8',
            // TODO 複檢：require_if的使用
            'passwordoublechk' => 'required_if:password, !=, null|same:password',
            'nickname' => 'required||max:10',
        ];
        $messages = [
            'password.min' =>'密碼至少要8個字符',
            'passwordoublechk.same' => '確認密碼必須要與密碼欄位相符',
            'nickname.required' => '暱稱欄位必填',
            'nickname.max' => '暱稱最多取名10個字以內',
        ];
        $validator = Validator::make($parameters, $rules, $messages);

        return $validator;
    }


//     // ----------------------------------------------------------------------------------------------------------
//     // 依照使用者要更改的項目，決定我的判斷標準，並進行驗證
//     // ----------------------------------------------------------------------------------------------------------
//     private function validateUpdate($password, $passwordoublechk)// todo 這邊其實也可以用request()->all()，這樣以後比較不用怕資料多增加
//     {
// /*----------------先看使用者密碼、確認密碼欄位有無填值------------------------------------------------------*/
//         $is_password_empty = (isset($password) || $password != "") ? false : true;
//         $is_passwordoublechk_empty = (isset($passwordoublechk) || $passwordoublechk != "") ? false : true;

// /*----------------想改密碼、暱稱----------------------------------------------------------------------------*/
//         if(!($is_password_empty) && !($is_passwordoublechk_empty)){
//             $rules = [
//                 'password' => 'min:8',
//                 'passwordoublechk' => 'nullable|same:password',
//                 'nickname' => 'required||max:50',
//             ];
//             $messages = [
//                 'password.min' => '密碼至少8字',
//                 'passwordoublechk.same' => '密碼兩次填寫不同',
//                 'nickname.required' => '暱稱欄位必填',
//                 'nickname.max' => '暱稱最多取名10個字以內',
//             ];
//         }
// /*----------------想改暱稱---------------------------------------------------------------------------------*/
//         elseif(($is_password_empty) && ($is_passwordoublechk_empty)){
//             $rules = [
//                 'nickname' => 'required||max:50',
//             ];
//             $messages = [
//                 'nickname.required' => '暱稱欄位必填',
//                 'nickname.max' => '暱稱最多取名10個字以內',
//             ];
//         }
// /*----------------其他填寫不完整隻情況---------------------------------------------------------------------*/
//         else{
//             $rules = [
//                 'password' => 'nullable|min:8',
//                 'passwordoublechk' => 'same:password',
//                 'nickname' => 'required||max:50',
//             ];
//             $messages = [
//                 'password.min' =>'密碼至少要8個字符',
//                 'passwordoublechk.same' => '確認密碼必須要與密碼欄位相符',
//                 'nickname.required' => '暱稱欄位必填',
//                 'nickname.max' => '暱稱最多取名10個字以內',
//             ];
//             //todo 研究一下require_if 
//         }
//         $parameters = request()->only('password', 'passwordoublechk', 'nickname');

//         $validator = Validator::make($parameters, $rules, $messages);

//         return $validator;
//     }
}
