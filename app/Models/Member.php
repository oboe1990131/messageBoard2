<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Member extends Authenticatable
{
    use HasFactory;
    public $table = "messageboard.member";
    // TODO 因為我的member表單的id我把他取成member_id了，我後面改回id後，我就不用這行了
    protected $primaryKey = 'member_id';

// ----------------------------------------------------------------------
// 
// ----------------------------------------------------------------------
    public static function checkReUse($request)
    {
        // 確定友直之後要放變數再放進來
        //  因為我這邊有require幫我驗暱稱有沒有填，所以，我這邊暱稱就不加驗是不是空字串了
        $useraccount = ($request->has('useraccount') && $request->input('useraccount') != "") ? trim($request->input('useraccount')) : "";
        $password = ($request->has('password') && $request->input('password') != "") ? trim($request->input('password')) : "";
        $nickname = ($request->has('nickname') && $request->input('nickname') != "") ? trim($request->input('nickname')) : "";

        $result_count = Member::select('useraccount', 'password', 'nickname')
                                ->from('member');

/*-----------------------------------註冊時，驗證帳號、暱稱是否重複使用--------------------------------------------------------------------------*/
        if(isset($useraccount) && $useraccount != "")
        {
            $useraccount =  trim($request->input('useraccount'));
            $result_count->where(function($query) use ($useraccount, $nickname)
            {
                $query->where('useraccount', $useraccount)->orwhere('nickname', $nickname);
            });
        }
/*---------------------------------更新時，驗證暱稱是否重複使用-----------------------------------------------------------------------------------*/
        elseif(($password == "") && ($nickname != ""))
        {
            $result_count->where(function($query) use ($useraccount, $nickname)
            {
                $query->where('useraccount', '<>', $useraccount)->where('nickname', $nickname);
            });
        }
/*--------------------------------更新時，驗證暱稱、密碼是否重複使用--------------------------------------------------------------------------------*/
        elseif(($password != "") && ($nickname != ""))
        {
            $useraccount = Usersession::get()['useraccount'];
            // TODO for原生雜湊方式
            // $password = hash('sha256', $password);
            $password = Hash::make($password);

            $result_count->where(function($query) use ($useraccount, $password, $nickname)
                {
                $query->where(function($query) use ($useraccount, $password){$query->where('useraccount', $useraccount)->where('password', $password);})

                    ->orwhere(function($query) use ($useraccount, $nickname){$query->where('useraccount', '!=', $useraccount)->where('nickname', $nickname);});
                });
        }
        // 得到一個Illuminate\Database\Eloquent\Builder Object，再將其轉成數字
        $is_reuse = ($result_count->count() >= 1) ? true : false;
        
        // TODO 複檢；回傳true/false
        return $is_reuse;
    }

    // ----------------------------------------------------------------------
    // 
    // ----------------------------------------------------------------------
    /**
     * 這邊主要是為了想要改成使用我自己Member這張model所需要的步驟之一
     * 原本是在User那個model當中的Authenticatable這個方法，裡面使用的方法
     * 主要似乎是要求我新增資料進table時，密碼欄位要填寫password，不可填寫pwd之類的縮寫，否則會收不到密碼
     *
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    // // ----------------------------------------------------------------------
    // // 
    // // ----------------------------------------------------------------------
    // /**
    //  * 在註冊新會員時，確認帳號、暱稱有無重複註冊
    //  * @param callable String $useraccount
    //  * @param callable String $nickname
    //  */
    // public static function checkAccountReUse(String $useraccount, String $nickname)
    // {
    //     $result_count = Member::select('useraccount', 'password', 'nickname')
    //                             ->from('member')
    //                             ->where(function($query) use ($useraccount, $nickname){
    //                                 $query->where('useraccount', $useraccount)
    //                                     ->orwhere('nickname', $nickname);
    //                             })
    //                             ->count();
    //     $is_reuse = ($result_count >= 1) ? true : false;

    //     return $is_reuse;
    // }

    // // ----------------------------------------------------------------------
    // // 
    // // ----------------------------------------------------------------------
    /**
     * 在更新新會員資料時，確認暱稱有無重複使用
     * @param callable String $nickname
     */
    // public static function chkNicknameReUse(String $nickname)
    // {
    //     $errors = [];

    //     $useraccount = self::getSessionData()['useraccount'];

    //     $result_count = Member::select('account', 'nickname')
    //                             ->from('member')
    //                             ->where('useraccount', '!=', $useraccount)
    //                             ->where('nickname', $nickname)
    //                             ->count();

    //     if($result_count >= 1){
    //         $errors[] = "重複使用";
    //         return $errors;
    //     }

    //     return $result_count;
    // }

    // // ----------------------------------------------------------------------
    // // 
    // // ----------------------------------------------------------------------
    // /**
    //  * 在更新新會員資料時，確認密碼、暱稱有無重複使用
    //  * @param callable String $password
    //  * @param callable String $nickname
    //  */
    // public static function checkBothReUse(String $password, String $nickname){
    //     $errors = [];

    //     $useraccount = self::getSessionData()['useraccount'];
    //     $password = Hash::make($password);
    //     // todo 這邊目前還沒完善，因為laravel每次的雜湊都不一樣，所以，我同樣的密碼，雜湊出來都不一樣，所以，永遠查不到我重複使用密碼，先暫時使用搭出原生php版本的sha256雜湊方式。

    //     $result_count = Member::select('useraccount', 'password', 'nickname')
    //                             ->from('member')
    //                             ->where(function($query) use ($useraccount, $password){
    //                                 $useraccount = self::getSessionData()['useraccount'];

    //                                 $query->where('useraccount', $useraccount)
    //                                     ->where('password', $password);
    //                             })
                                
    //                             ->orWhere(function ($query) use ($useraccount, $nickname){
    //                                 $useraccount = self::getSessionData()['useraccount'];

    //                                 $query->where('useraccount', '!=', $useraccount)
    //                                         ->where('nickname', $nickname);
    //                             })
    //                             ->count();

    //     if($result_count >= 1){
    //         $errors[] = "重複使用";
    //         return $errors;
    //     }

    //     return $result_count;
    // }
}
