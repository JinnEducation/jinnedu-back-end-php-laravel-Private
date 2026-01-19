document.addEventListener('DOMContentLoaded', function () {
    const cards = Array.from(document.querySelectorAll('.tutor-card'));
    if (!cards.length) return;

    // عناصر الفلتر
    const filters = {
        subject: document.getElementById('filterSubject'),
        priceRange: document.getElementById('filterPriceRange'),
        nativeLanguage: document.getElementById('filterNativeLanguage'),
        availability: document.getElementById('filterAvailability'),
        specialization: document.getElementById('filterSpecialization'),
        country: document.getElementById('filterCountry'),
        alsoSpeaks: document.getElementById('filterAlsoSpeaks'),
        sortBy: document.getElementById('filterSortBy'),
        fullName: document.getElementById('filterFullName'),
    };

    function parsePriceRange(value) {
        if (!value) return null;
        const parts = value.split('-');
        if (parts.length !== 2) return null;

        const min = parseFloat(parts[0]);
        const max = parseFloat(parts[1]);
        if (isNaN(min) || isNaN(max)) return null;

        return { min, max };
    }

    function matchesFilter(card, filterName, filterValue) {
        if (!filterValue) return true;

        // نخلي المقارنات lower-case لو كانت نصية
        filterValue = String(filterValue).toLowerCase();

        switch (filterName) {
            case 'subject': {
                // من data-subject-id
                const value = (card.dataset.subjectId || '').toLowerCase();
                return !filterValue || value === filterValue;
            }

            case 'nativeLanguage': {
                // من data-native-language-id
                const value = (card.dataset.nativeLanguageId || '').toLowerCase();
                return !filterValue || value === filterValue;
            }

            case 'availability': {
                // من data-availability-periods = "morning,afternoon"
                const value = (card.dataset.availabilityPeriods || '').toLowerCase();
                if (!filterValue) return true;
                const availableSlots = value
                    .split(',')
                    .map(v => v.trim())
                    .filter(Boolean);
                return availableSlots.includes(filterValue);
            }

            case 'specialization': {
                // من data-specialization-ids = "1,2,3"
                const value = (card.dataset.specialization || '').toLowerCase();
                if (!filterValue) return true;
                const specs = value
                    .split(',')
                    .map(v => v.trim())
                    .filter(Boolean);
                return specs.includes(filterValue);
            }

            case 'country': {
                // من data-country-id
                const value = (card.dataset.country || '').toLowerCase();
                return !filterValue || value === filterValue;
            }

            case 'alsoSpeaks': {
                // من data-also-speaks-ids = "1,3,5"
                const value = (card.dataset.alsoSpeaks || '').toLowerCase();
                if (!filterValue) return true;
                const langs = value
                    .split(',')
                    .map(v => v.trim())
                    .filter(Boolean);
                return langs.includes(filterValue);
            }

            case 'priceRange': {
                if (!filterValue) return true;
                const range = parsePriceRange(filterValue);
                if (!range) return true;

                const cardPrice = parseFloat(card.dataset.price || '0');
                return cardPrice >= range.min && cardPrice <= range.max;
            }

            case 'fullName': {
                if (!filterValue) return true;
                // من data-full-name
                const name = (card.dataset.fullName || '').toLowerCase();
                return name.includes(filterValue);
            }

            default:
                return true;
        }
    }

    function applyFilters() {
        const activeFilters = {
            subject: filters.subject ? filters.subject.value : '',
            priceRange: filters.priceRange ? filters.priceRange.value : '',
            nativeLanguage: filters.nativeLanguage ? filters.nativeLanguage.value : '',
            availability: filters.availability ? filters.availability.value : '',
            specialization: filters.specialization ? filters.specialization.value : '',
            country: filters.country ? filters.country.value : '',
            alsoSpeaks: filters.alsoSpeaks ? filters.alsoSpeaks.value : '',
            sortBy: filters.sortBy ? filters.sortBy.value : '',
            fullName: filters.fullName ? filters.fullName.value.trim().toLowerCase() : '',
        };
        console.log(activeFilters.country)

        // فلترة الكروت
        let filteredCards = cards.filter(card => {
            return (
                matchesFilter(card, 'subject', activeFilters.subject) &&
                matchesFilter(card, 'priceRange', activeFilters.priceRange) &&
                matchesFilter(card, 'nativeLanguage', activeFilters.nativeLanguage) &&
                matchesFilter(card, 'availability', activeFilters.availability) &&
                matchesFilter(card, 'specialization', activeFilters.specialization) &&
                matchesFilter(card, 'country', activeFilters.country) &&
                matchesFilter(card, 'alsoSpeaks', activeFilters.alsoSpeaks) &&
                matchesFilter(card, 'fullName', activeFilters.fullName)
            );
        });

        // Sort (ترتيب)
        switch (activeFilters.sortBy) {
            case 'price_low_high':
                filteredCards.sort((a, b) => {
                    const pa = parseFloat(a.dataset.price || '0');
                    const pb = parseFloat(b.dataset.price || '0');
                    return pa - pb;
                });
                break;

            case 'price_high_low':
                filteredCards.sort((a, b) => {
                    const pa = parseFloat(a.dataset.price || '0');
                    const pb = parseFloat(b.dataset.price || '0');
                    return pb - pa;
                });
                break;

            case 'rating_high_low':
                filteredCards.sort((a, b) => {
                    const ra = parseFloat(a.dataset.rating || '0');
                    const rb = parseFloat(b.dataset.rating || '0');
                    return rb - ra;
                });
                break;

            case 'most_popular':
                filteredCards.sort((a, b) => {
                    const sa = parseInt(a.dataset.students || '0', 10);
                    const sb = parseInt(b.dataset.students || '0', 10);
                    return sb - sa;
                });
                break;

            default:
                // بدون ترتيب إضافي
                break;
        }

        // إظهار/إخفاء الكروت + الحفاظ على ترتيب الـ DOM
        const container = document.getElementById('tutorsListContainer');
        if (!container) return;

        // نخفي الكل
        cards.forEach(card => {
            card.style.display = 'none';
        });

        // نعرض اللي بعد الفلترة ونرتّب
        filteredCards.forEach(card => {
            card.style.display = '';
            container.appendChild(card);
        });
    }

    // ربط الأحداث بالفلاتر
    Object.keys(filters).forEach(key => {
        const el = filters[key];
        if (!el) return;

        const eventName = (el.tagName === 'SELECT') ? 'change' : 'input';
        el.addEventListener(eventName, applyFilters);
    });

    // تشغيل أولي
    applyFilters();
});
