<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usermessage;
use App\Models\Emotion;
use App\Models\Usersession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use session;


class MessageboardController extends Controller
{
    public function index(Request $request)
    {
/*----------------取得所需Session data-----------------------------------------*/
        $member_id = Usersession::get()['member_id'];
        $nickname = Usersession::get()['nickname'];

/*----------------先判斷前端搜尋bar裡面是否有東西，如果有輸入東西，就是想要搜尋特定內容，就進去幫我找，否則就輸出全部的內容------*/
        $find = $request->has('find') ? trim($request->input('find')) : "";

        $messages = Usermessage::getMessage($find);
        // 把查詢結果一併保留，帶到主畫面搜尋欄位去擺放
        $request->flash();

        return view('messageboards.index', compact('member_id', 'nickname', 'messages'));
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function create()
    {
/*----------------取得所需data-----------------------------------------------*/
        $nickname = Usersession::get()['nickname'];
        $emotions = Emotion::all();

        return view('messageboards.create', compact('nickname', 'emotions'));
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {
/*----------------驗證機制---------------------------------------------------*/
        try{
            $validator = $this->validateCreate($request);

            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator->errors())
                            ->withInput();
            }
/*---------驗證通過後，把訊息送進資料庫裡面-------------------------------------*/
            $message = new Usermessage();

            $message->message = $request->input('message');
            $message->auther_id = Usersession::get()['member_id'];
            $message->mood = $request->input('emotion');

            $message->save();

            return redirect()->route('messageboard.index');

        }catch(\Exception $e){
            var_dump(trans('error.createfail'));
        }
        // TODO 練習try catch
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function edit(Usermessage $messageboard)
    {
/*----------------驗證id-----------------------------------------------------*/
        $member_id = Usersession::get()['member_id'];
        $message_id = $messageboard->id;
        $id_match = Usermessage::checkMessageId($member_id, $message_id);

        if($id_match){
            $nickname = Usersession::get()["nickname"];
            $emotions = Emotion::all();
            $message = $messageboard->message;

            return view('messageboards.edit', compact('messageboard', 'nickname', 'emotions', 'message'));
        }
        return redirect()->route('messageboard.index');
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function update(Request $request, Usermessage $messageboard)
    {
/*---------------------------驗證id------------------------------------------*/
        try{
            $member_id = Usersession::get()["member_id"];
            $message_id = $messageboard->id;
            $id_match = Usermessage::checkMessageId($member_id, $message_id);

            if(!$id_match){
                return redirect()->route('messageboard.index');
            }
/*-------------------------驗證格式--------------------------------------------*/
            $validator = $this->validateEdit($request);

            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator->errors())
                            ->withInput();
            }
/*--------------------驗證通過後，把訊息送進資料庫裡面------------------------*/
                $messageboard->message = htmlspecialchars($request->input('message'));
                $messageboard->auther_id = Usersession::get()['member_id'];
                $messageboard->mood = $request->input('emotion');
                $messageboard->save();
                return redirect()->route('messageboard.index');

        }catch(\Exception $e){
            var_dump(trans('error.updatefail'));
        }
            // TODO 加try catch
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    public function destroy(Usermessage $messageboard)
    {
/*----------------驗證id-----------------------------------------------------*/
        try{
            $member_id = Usersession::get()['member_id'];
            $message_id = $messageboard->id;
            $id_match = Usermessage::checkMessageId($member_id, $message_id);

            if($id_match){
                $messageboard->delete();
                return redirect()->route('messageboard.index');
            }
            return redirect()->route('messageboard.index');

        }catch(\Exception $e){
            var_dump(trans('error.deletefail'));
        }
        // TODO 加try catch

    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    private function validateCreate(Request $request)
    {
        $parameters = $request->all();
        $rules = [
            'message' => 'required|max:255',
        ];
        $messages = [
            'message.required' => '留言內容為必填',
            'message.max' => '我們的留言內容最多只有讓你填到255個字',
            'message.alpha_dash' => '我們只允許少數幾種符號',
        ];
        $validator = Validator::make($parameters, $rules, $messages);

        return $validator;
    }

// ----------------------------------------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------------------------------------
    private function validateEdit(Request $request)
    {
        $parameters = $request->all();
        $rules = [
            'message' => 'required|max:255',
        ];
        $messages = [
            'message.required' => '留言內容為必填',
            'message.max' => '我們的留言內容最多只有讓你填到255個字',
            'message.alpha_dash' => '我們只允許少數幾種符號',
        ];

        $validator = Validator::make($parameters, $rules, $messages);

        return $validator;
    }
}