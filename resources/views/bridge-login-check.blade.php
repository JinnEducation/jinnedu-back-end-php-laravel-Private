<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            window.close();

            // fallback لو ما تسكر
            setTimeout(function () {
                document.body.innerHTML = "<p style='text-align:center;margin-top:40px'>You can close this tab.</p>";
            }, 300);
        }, 500);
    });
</script>
