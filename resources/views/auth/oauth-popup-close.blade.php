<script>
    window.opener.postMessage(
        @json($data),
        "{{ url('/') }}"
    );

    window.close();
</script>
