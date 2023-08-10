@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ asset('indexstyle.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
@section('title', 'message home-page')

@section('content')
@include('layouts.messagesuccess')
<div>
    <h1>心情留言板</h1>
    <p>親愛的會員～{{ $nickname }}～歡迎回來</p>
</div>
<div>
    <a href="{{ route('messageboard.index') }}">回首頁</a>
</div>
<div>
    <a href="{{ route('messageboard.create') }}">新增留言</a><br>
</div>

<div>
    <form action="{{ route('messageboard.index') }}" method="get">
        請輸入欲查詢：
        <input type="text" name="find" value="{{ old('find') }}">
        <button type="submit">送出</button>
    </form>
</div>
<hr>
<div>
    <table>
        <thead>
            <tr>
                <th>流水號</th>
                <th>作者</th>
                <th>內容</th>
                <th>心情</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($messages as $messageboard)
                <tr>
                    <td>{{ $messageboard->id }}</td>
                    <td>{{ $messageboard->nickname }}</td>
                    <td class="message">{{ ($messageboard->message) }}</td>
                    <td>{{ $messageboard->mood }}</td>
                    <td>@include('layouts.operator')</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination">
    {!! $messages->links('pagination::bootstrap-4') !!}
</div>

@endsection