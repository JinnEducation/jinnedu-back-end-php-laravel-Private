

   
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('favTutorBtn');
            if (!btn) return;

            btn.addEventListener('click', async () => {
                const refId = btn.dataset.ref;
                const type  = btn.dataset.type; // 1 tutor

                btn.disabled = true;

                try {
                    const res = await fetch("{{ route('site.user_favorites.toggle') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            ref_id: parseInt(refId),
                            type: parseInt(type),
                        })
                    });

                    if (res.status === 401) {
                        // لو مش عامل login
                        window.location.href = "{{ route('login') }}";
                        return;
                    }

                    const data = await res.json();

                    // تغيير شكل الزر (اختياري)
                    if (data.status === 'added') {
                        btn.classList.add('bg-primary','text-white');
                        btn.classList.remove('text-primary','border-primary');
                    } else {
                        btn.classList.remove('bg-primary','text-white');
                        btn.classList.add('text-primary','border','border-primary');
                    }

                } catch (e) {
                    console.error(e);
                    alert('Error saving favorite');
                } finally {
                    btn.disabled = false;
                }
            });
        });
    

