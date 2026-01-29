$(function () {

    const $btn = $('#favTutorBtn');
    if (!$btn.length) return;


    $btn.on('click', function () {

        const refId = parseInt($btn.data('ref'));
        const type = parseInt($btn.data('type')); // 1 = tutor

        $btn.prop('disabled', true);

        $.ajax({
            url: toggleUrl,
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                ref_id: refId,
                type: type
            }),
            success: function (data) {
                if (data.status === 'added') {
                    $btn
                        .text(savedWords || 'Saved to your list')
                        .addClass('bg-primary text-white')
                        .removeClass('text-primary border border-primary');
                } else {
                    $btn
                        .text(unSavedWords || 'Save to my list')
                        .removeClass('bg-primary text-white')
                        .addClass('text-primary border border-primary');
                }
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    window.location.href = loginUrl;
                    return;
                }
                alert('Error saving favorite');
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });

    });

});


