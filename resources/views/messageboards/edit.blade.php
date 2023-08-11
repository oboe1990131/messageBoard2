@extends('layouts.default')
@section('title', 'edit message')
<link rel="stylesheet" type="text/css" href="{{ asset('writestyle.css') }}">

@section('content')
<div>
    <h2 id="h">心情抒發區<br></h2><hr/>
</div>
<div>
    <a href="{{ route('messageboard.index')}}">回首頁</a>
</div>

@include('layouts.messagerror')

<div>
    <form action="{{ route('messageboard.update', $messageboard->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <table>
            <tr>
                <td>您的大名:</td>
                <td>{{ $nickname }}</td>
            </tr>
            
            <tr>
                <td>您的留言:</td>
                <td><textarea cols="100" rows="10" name="message">{!! strip_tags(nl2br($message)) !!}</textarea><br><br></td> 
            </tr>

            <tr>
                <td>您現在的心情：</td>
                <td>
                    @foreach($emotions as $emotion)
                        <input type="radio" name="emotion" value="{{ $emotion['id'] }}" id="mood_{{ $emotion['id'] }}" @if($messageboard->mood == $emotion['id']) checked @endif  {{ $messageboard->mood == $emotion['id'] ? "checked" : "" }}>
                        <label for="mood_{{ $emotion['id'] }}">{{ $emotion['mood'] }}</label>
                    @endforeach
                </td>
            </tr>
        </table>

        <button type="submit">送出</button>
    </form>
</div>
@endsection