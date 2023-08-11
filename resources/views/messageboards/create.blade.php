@extends('layouts.default')
@section('title', 'create message')
<link rel="stylesheet" type="text/css" href="{{ asset('writestyle.css') }}">


@section('content')

<div>
    <h2>心情抒發區<br></h2><hr/>
</div>
<div>
    <a href="{{ route('messageboard.index')}}">回首頁</a>
</div>

@include('layouts.messagerror')

<div>
    <form action="{{ route('messageboard.store') }}" method="post">
        @csrf
        <table>
            <tr>
                <td>您的大名:</td>
                <td>{{ $nickname }}</td>
            </tr>
            
            <tr>
                <td>您的留言:</td>
                <td><textarea cols="100" rows="10" name="message">{{ old('message') }}</textarea><br><br></td>
            </tr>

            <tr>
                <td>您現在的心情:</td>
                <td>
                    @foreach($emotions as $emotion)
                        <label>
                            <input type="radio" name="emotion"  value="{{ $emotion['id'] }}">
                            {{ $emotion['mood'] }}
                        </label>
                    @endforeach
                </td>
            </tr>
        </table>

        <div>
            <button type="submit">送出</button>
        </div>

    </form>
</div>
@endsection