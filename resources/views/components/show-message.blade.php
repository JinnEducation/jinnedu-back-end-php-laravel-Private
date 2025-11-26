@if (session()->has($type))
    <script>
        console.log("{{ session($type) }}");
        console.log("{{ $type }}");
        showMessage("{{ session($type) }}", "{{ $type }}");
    </script>
@endif
