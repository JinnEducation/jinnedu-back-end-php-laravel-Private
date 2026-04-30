{{ $data['greeting'] ?? 'Hello' }}, {{ $data['user_name'] }}
<br><br>
{{ $data['message'] ?? '' }}
@if (!empty($data['actions']) && is_array($data['actions']))
    <br><br>
    @foreach ($data['actions'] as $action)
        @if (!empty($action['url']))
            <a href="{{ $action['url'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:10px 16px;background:#0f766e;color:#ffffff;text-decoration:none;border-radius:6px;">
                {{ $action['text'] ?? $action['url'] }}
            </a>
        @endif
    @endforeach
@endif
@if (!empty($data['action_url']))
    <br><br>
    <a href="{{ $data['action_url'] }}" style="display:inline-block;padding:10px 16px;background:#0f766e;color:#ffffff;text-decoration:none;border-radius:6px;">
        {{ $data['action_text'] ?? $data['action_url'] }}
    </a>
    <br><br>
    <a href="{{ $data['action_url'] }}">{{ $data['action_url'] }}</a>
@endif
<br><br>
{{ $data['thanks'] ?? 'Thank you' }}!
