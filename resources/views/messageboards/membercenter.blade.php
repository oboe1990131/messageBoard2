@extends('layouts.default')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="{{ asset('membercenterstyle.css') }}">
@section('title', 'Regist')

@section('content')
<div id="frm">
    <form action="{{ route('membercenter.update', $member_id) }}" method="post" onsubmit="return confirm('確認送出？')">
        @csrf
        {{ method_field('PATCH') }}
        <div>
            <h1 style="text-align:center">會員中心<br></h1><hr/>
        </div>
        <div>
            <p>
                <label>帳號:<span class="account-value">{{ $useraccount }}</span></label>
            </p>
        </div>
        <div>
            <p>
                <label>密碼:</label>
                <input type="password"name="password"/>
            </p>
        </div>
        <div>
            <p>
                <label>確認密碼:</label>
                <input type="password"name="passwordoublechk"/>
            </p>
        </div>
        <div>
            <p>
                <label>暱稱:</label>
                <input type="text"name="nickname" value="{{ $nickname }}"/><hr>
            </p>
        </div>
        <div>
            @include('layouts.validatorerror')
        </div>
        <div>
            <button type="submit">送出</button>
        </div>
        <div>
            <a href="{{ route('messageboard.index') }}">回首頁</a>
        </div>
    </form>
</div>
@endsection