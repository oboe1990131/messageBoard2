@extends('layouts.default')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="{{ asset('loginstyle.css') }}">
@section('title', 'login')

@include('layouts.messagerror')

@section('content')
<div id="frm">
    <form action="{{ url('/toLogin') }}" method="post">
        @csrf
        <div>
            <p>
                <label>Username:</label>
                <input type="text" id="user" name="useraccount" value="{{ old('useraccount') }}"/>
            </p>
        </div>
        <div>
            <p>
                <label>Password:</label>
                <input type="password" id="pass" name="password" value="{{ old('password') }}"/>
            </p>
        </div>
        <div>
            <p>
                <input type="submit" id="btn" value="Login">
            </p>
        </div>
        <div>
            <p>
                <a href="{{ url('/regist') }}">註冊會員</a>
            </p>
        </div>
    </form>
</div>
@endsection
