@if($messageboard->auther_id == $member_id)
    <div class="flex">
        <a href="{{ route('messageboard.edit', $messageboard) }}">編輯</a>

        <form action="{{ route('messageboard.destroy', $messageboard) }}" method="POST" onsubmit="return confirm('確認刪除這篇留言嗎?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-2 bg-red-500 text-red-100 rounded">刪除</button>
        </form>
    </div>
@endif