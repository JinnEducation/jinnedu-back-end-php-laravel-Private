// Tutors List Page Functionality
$(document).ready(function() {
    console.log('Tutors List Page Loaded');

    // Day ID to day name mapping
    const dayIdToName = {
        1: 'sunday',
        2: 'monday',
        3: 'tuesday',
        4: 'wednesday',
        5: 'thursday',
        6: 'friday',
        7: 'saturday'
    };

    // Convert time to minutes
    function timeToMinutes(time) {
        const [hours, minutes] = time.split(':').map(Number);
        return hours * 60 + minutes;
    }

    // Get periods for a time slot
    function getPeriodsForTimeSlot(hourFrom, hourTo) {
        const fromMin = timeToMinutes(hourFrom);
        const toMin = timeToMinutes(hourTo);
        const periods = [];

        // Morning: 6AM - 12PM (360 - 720 minutes)
        if (fromMin < 720 && toMin > 360) {
            periods.push('morning');
        }

        // Afternoon: 12PM - 6PM (720 - 1080 minutes)
        if (fromMin < 1080 && toMin > 720) {
            periods.push('afternoon');
        }

        // Evening: 6PM - 10PM (1080 - 1320 minutes)
        if (fromMin < 1320 && toMin > 1080) {
            periods.push('evening');
        }

        // Night: 10PM - 6AM (1320 - 1440 or 0 - 360)
        if (fromMin >= 1320 || toMin <= 360 || (fromMin < 360 && toMin > 1320)) {
            periods.push('night');
        }

        return periods;
    }

    // Tutor Card Click - Add Active State & Update Schedule
    $('.tutor-card').on('click  mouseenter', function(e) {
        // Don't trigger if clicking buttons
        if ($(e.target).closest('button, a').length) {
            return;
        }
        
        // Remove active class from all cards
        $('.tutor-card').removeClass('ring-2 ring-primary');
        
        // Add active class to clicked card
        $(this).addClass('ring-2 ring-primary');

        // Get tutor data
        const tutorName = $(this).data('tutor-name') || 'Tutor';
        const tutorSubject = $(this).data('tutor-subject') || '-';
        const tutorAvatar = $(this).data('tutor-avatar') || './assets/imgs/tutors/1.jpg';
        const tutorSlug = $(this).data('tutor-slug') || '#';
        const availability = $(this).data('availability') || [];

        // Update header
        $('#scheduleTutorName').text(tutorName);
        $('#scheduleTutorSubject').text(tutorSubject);
        $('#scheduleTutorAvatar').attr('src', tutorAvatar);
        $('#viewFullScheduleBtn').off('click').on('click', function() {
            window.location.href = 'tutor_jinn/' + tutorSlug;
        });

        // Update schedule grid
        updateScheduleGrid(availability);
    });

    function updateScheduleGrid(availability) {
        // Reset all cells to gray
        $('#scheduleGrid [data-day]').removeClass('bg-primary').addClass('bg-gray-100');

        if (!availability || availability.length === 0) {
            return;
        }

        // Process each availability slot
        availability.forEach(slot => {
            const dayId = slot.day_id || 0;
            const dayName = dayIdToName[dayId];
            const hourFrom = slot.hour_from || '';
            const hourTo = slot.hour_to || '';

            if (!dayName || !hourFrom || !hourTo) {
                return;
            }

            // Get which periods this time slot covers
            const periods = getPeriodsForTimeSlot(hourFrom, hourTo);

            // Mark the specific day cell in each period row
            periods.forEach(period => {
                const $row = $('[data-period="' + period + '"]');
                if ($row.length) {
                    const $cell = $row.find('[data-day="' + dayName + '"]');
                    if ($cell.length) {
                        $cell.removeClass('bg-gray-100').addClass('bg-primary');
                    }
                }
            });
        });
    }

    // Hover Effect Enhancement
    $('.tutor-card').hover(
        function() {
            $(this).find('img').addClass('scale-105');
        },
        function() {
            $(this).find('img').removeClass('scale-105');
        }
    );

    $('#tutorsListContainer .tutor-card').first().trigger('click');
});

