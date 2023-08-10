<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Usersession extends Authenticatable
{
    use HasFactory;
    // ----------------------------------------------------------------------
    // 
    // ----------------------------------------------------------------------
    /**
     * 把Session data統整在這邊，大家需要用到甚麼，就呼叫來使用
     * 
     * @return Session::get
     */
    public static function get()
    {
        return array('user' => Session::get('user'),
                    'useraccount' => Session::get('useraccount'),
                    'member_id' => Session::get('member_id'), 
                    'nickname' => Session::get('nickname')
                    );
    }
}
