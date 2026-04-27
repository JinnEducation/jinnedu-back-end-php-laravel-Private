Hello, {{ $data['user_name'] }}
<br><br>
{{ $data['message'] ?? '' }}
@if (!empty($data['action_url']))
    <br><br>
    <a href="{{ $data['action_url'] }}" style="display:inline-block;padding:10px 16px;background:#0f766e;color:#ffffff;text-decoration:none;border-radius:6px;">
        {{ $data['action_text'] ?? $data['action_url'] }}
    </a>
    <br><br>
    <a href="{{ $data['action_url'] }}">{{ $data['action_url'] }}</a>
@endif
<br><br>
Thank you!
