@extends('layouts.app')

@section('title', 'Regist')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="{{ asset('loginstyle.css') }}">
<style>
    button{
        width: 80px; height: 30px;
    }
</style>

@section('content')
<div id="frm">
    <form action="{{ url('/registtodb') }}" method="post">
        @csrf
        <div>
            <h1 style="text-align:center">會員註冊<br></h1><hr/>
        </div>
        <div>
            <p>
                <label>帳號:</label>
                <input type="text" name="useraccount"/>
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
                <input type="text"name="nickname"/><hr>
            </p>
        </div>
        <div>
            @include('layouts.validatorerror')
        </div>
        <div>
            <button type="submit">送出</button>
        </div>
        <div>
            <a href="{{ url('/login') }}">回登入頁</a>
        </div>
    </form>
</div>
@endsection