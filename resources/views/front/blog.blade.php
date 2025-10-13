<x-front-layout>

 <!-- Hero Section -->
    <section class="flex overflow-hidden relative items-center bg-white mt-[120px] py-10">
        <!-- Main Container -->
        <div class="container z-10 px-4 mx-auto w-full">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-14">
                <ul class="flex items-center space-x-1 text-sm font-light">
                    <!-- Home -->
                    <li>
                        <a href="{{route('home')}}" class="transition-colors text-primary-600 hover:text-primary-700">Home</a>
                    </li>
                    <li>
                        <span class="text-gray-400">
                            <i class="font-light fas fa-chevron-right"></i>
                        </span>
                    </li>
                    <!-- Current Page -->
                    <li>
                        <span class="text-gray-900">Blog</span>
                    </li>
                </ul>
            </nav>

            <!-- Section Title -->
            <h2 class="mb-7 text-3xl font-bold">Explore Our Blogs !</h2>

            <!-- Category Filter -->
            <div class="relative mx-[-30px]" dir="ltr">
                <!-- سهم يسار -->
                <div id="left-arrow"
                    class="flex absolute inset-y-0 left-0 items-center opacity-0 transition-opacity pointer-events-none md:-left-10 ps-2 text-primary">
                    <!-- Heroicons: chevron-left -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>

                <div id="filter-container"
                    class="flex overflow-x-auto overflow-y-hidden relative flex-nowrap gap-2 px-8 whitespace-nowrap scrollbar-hide cursor-grab active:cursor-grabbing">
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-white bg-primary border border-primary transition-all duration-300 text-md category-blogs-btn active hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="all" id="all-blogs-btn">
                        All
                    </button>
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="english">
                        English
                    </button>
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="tech">
                        Tech
                    </button>
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="arabic">
                        Arabic
                    </button>
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="math">
                        Math
                    </button>
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="programming">
                        Programming
                    </button>
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="physics">
                        Physics
                    </button>
                    <button
                        class="w-[165px] rounded-sm px-2 lg:px-5 flex-shrink-0 py-2 text-gray-600 border border-gray-200 transition-all duration-300 text-md category-blogs-btn hover:text-white hover:bg-primary hover:scale-105 hover:cursor-pointer"
                        data-type="chemistry">
                        Chemistry
                    </button>                
                </div>

                <!-- سهم يمين -->
                <div id="right-arrow"
                    class="flex absolute inset-y-0 right-0 items-center opacity-0 transition-opacity pointer-events-none md:-right-10 pe-2 text-primary">
                    <!-- Heroicons: chevron-right -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Courses Section -->
    <section class="py-8 md:py-16">
        <div class="container mx-auto">
            <!-- Courses Grid -->
            <!-- من هنا -->
             
          <div class="grid grid-cols-1 gap-10 mb-12 md:grid-cols-2 lg:grid-cols-3" id="coursesGridBlogs">
  @foreach($blogs as $blog)
    <div class="block overflow-hidden bg-white rounded-md shadow-lg transition-all duration-300 course-blogs-card hover:shadow-lg hover:scale-102"
         data-type="{{ optional($blog->category)->slug }}">
      <div class="overflow-hidden relative h-54">
        <img src="{{ $blog->image_url ?? 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=250&fit=crop' }}"
             alt="{{ $blog->title }}" class="object-cover w-full h-full" />
      </div>
      <div class="p-4 pb-0">
        <h3 class="mb-2 font-semibold text-black text-md">
          {{ $blog->title }}
        </h3>
        <p class="mb-4 text-[13px] text-gray-400">
          {{ $blog->excerpt ?? Str::limit(strip_tags($blog->body ?? ''), 120) }}
        </p>
        <div class="flex justify-between items-center">
          <div class="flex gap-2 items-center">
            <i class="text-sm fas fa-star text-[#FFC700]"></i>
            <span class="text-sm text-gray-800 me-1">{{ number_format($blog->rating ?? 0, 1) }}/5</span>
            <span class="text-sm text-gray-500">({{ $blog->reviews_count ?? 0 }})</span>
          </div>
          <a href="{{ $blog->url ?? '#' }}"
             class="text-sm font-medium text-[#0553FC] underline hover:text-primary hover:mr-3 rtl:hover:ml-3 transition-all duration-300">
            Load More
          </a>
        </div>
        <div class="py-2 mt-3 border-t border-[#E5E7EB]">
          <div class="flex justify-between items-center transition-all duration-300">
            <a href="#"
               class="p-1.5 rounded-full transition-all duration-300 hover:scale-105 hover:ml-1 rtl:hover:mr-1">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.894 7.46942C15.6073 7.47286 16.3064 7.27001 16.908 6.88508C17.5097 6.50015 17.9884 5.94941 18.287 5.29857C18.5857 4.64773 18.6917 3.92429 18.5923 3.21458C18.493 2.50487 18.1925 1.83887 17.7268 1.29606C17.2611 0.753244 16.6498 0.356549 15.9658 0.153311C15.2819 -0.0499258 14.5542 -0.0511209 13.8695 0.149869C13.1849 0.350858 12.5723 0.745543 12.1049 1.28682C11.6374 1.8281 11.3347 2.49311 11.2331 3.20249L6.20629 6.53574C5.67256 6.0519 5.01065 5.73396 4.30075 5.62045C3.59085 5.50694 2.86343 5.60272 2.20661 5.8962C1.54978 6.18967 0.991745 6.66825 0.600098 7.27394C0.20845 7.87964 0 8.58646 0 9.30878C0 10.0311 0.20845 10.7379 0.600098 11.3436C0.991745 11.9493 1.54978 12.4279 2.20661 12.7214C2.86343 13.0148 3.59085 13.1106 4.30075 12.9971C5.01065 12.8836 5.67256 12.5657 6.20629 12.0818L11.2238 15.4431C11.3555 16.3327 11.8022 17.1447 12.4819 17.7299C13.1616 18.3151 14.0286 18.6342 14.9237 18.6285C15.8187 18.6228 16.6816 18.2927 17.3539 17.6989C18.0262 17.1051 18.4626 16.2874 18.583 15.3962C18.7034 14.5049 18.4997 13.6 18.0093 12.8476C17.5189 12.0952 16.7747 11.5459 15.9136 11.3006C15.0524 11.0554 14.1321 11.1306 13.3218 11.5125C12.5114 11.8945 11.8655 12.5575 11.5026 13.3796L7.23767 10.5226C7.53056 9.73692 7.53056 8.8713 7.23767 8.08565L11.5026 5.22858C11.7908 5.89227 12.2649 6.45745 12.867 6.8553C13.4691 7.25315 14.1734 7.46651 14.894 7.46942Z" fill="#1B449C"/>
              </svg>
            </a>

            <div class="flex gap-1 items-center">
              <i class="text-lg">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9.50002 19C4.26134 19 0 14.7387 0 9.50002C0 4.26134 4.26134 0 9.50002 0C14.7387 0 19 4.26134 19 9.50002C19 14.7387 14.7379 19 9.50002 19ZM9.50002 1.52C5.0996 1.52 1.52 5.0996 1.52 9.50002C1.52 13.9004 5.0996 17.48 9.50002 17.48C13.9004 17.48 17.48 13.9004 17.48 9.50002C17.48 5.0996 13.8996 1.52 9.50002 1.52ZM9.4392 10.1992H4.56C4.35846 10.1992 4.16513 10.1191 4.0226 9.97659C3.88008 9.83407 3.80001 9.64079 3.80001 9.4392C3.80001 9.23765 3.88008 9.04433 4.0226 8.9018C4.16513 8.75927 4.35846 8.6792 4.56 8.6792H8.6792V3.04002C8.6792 2.83843 8.75927 2.64513 8.9018 2.5026C9.04433 2.36007 9.23765 2.28 9.4392 2.28C9.64079 2.28 9.83407 2.36007 9.97659 2.5026C10.1191 2.64513 10.1992 2.83843 10.1992 3.04002V9.4392C10.1992 9.64079 10.1191 9.83407 9.97659 9.97659C9.83407 10.1191 9.64079 10.1992 9.4392 10.1992Z" fill="#1B449C"/>
                </svg>
              </i>
              <span class="text-sm text-gray-400">{{ $blog->date }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<!-- Pagination Section -->
<div class="flex justify-between items-center">
  <div class="flex items-center">
    <div class="relative">
      <select id="perPageSelect"
        class="py-3.5 pr-10 pl-24 text-black rounded-md border border-gray-200 appearance-none cursor-pointer text-md hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-32">
        <option value="6"  {{ (int)$perPage===6  ? 'selected':'' }}>6</option>
        <option value="9"  {{ (int)$perPage===9  ? 'selected':'' }}>9</option>
        <option value="12" {{ (int)$perPage===12 ? 'selected':'' }}>12</option>
      </select>
      <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
        <span class="text-sm font-medium text-gray-700">PER PAGE</span>
      </div>
      <div class="flex absolute inset-y-0 right-0 items-center pr-2 pointer-events-none">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
      </div>
    </div>
  </div>

  <div class="flex gap-1 justify-center items-center" id="paginationBlogs">
    <button class="flex justify-center items-center w-8 h-8 text-black rounded-full transition-all duration-200 cursor-pointer hover:text-white hover:bg-primary"
            data-page="prev" {{ $blogs->onFirstPage() ? 'disabled' : '' }}>
      <i class="fas fa-chevron-left"></i>
    </button>

    <div id="pagesNumbers" class="flex gap-1">
      @for ($i = 1; $i <= $blogs->lastPage(); $i++)
        <button
          class="flex justify-center items-center w-8 h-8 rounded-full transition-all duration-200 cursor-pointer {{ $i === $blogs->currentPage() ? 'text-white bg-primary' : 'text-black hover:text-white hover:bg-primary' }}"
          data-page="{{ $i }}">
          {{ $i }}
        </button>
      @endfor
    </div>

    <button class="flex justify-center items-center w-8 h-8 text-black rounded-full transition-all duration-200 cursor-pointer hover:text-white hover:bg-primary"
            data-page="next" {{ $blogs->currentPage() === $blogs->lastPage() ? 'disabled' : '' }}>
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>

  <div class="hidden w-32 md:block"></div>
</div>
             <!--لهنااااااااااااااااااا-->
        </div>
    </section>

 
   <script>
(() => {
  // ✳️ مهم: حط السيليكتور الصحيح لعناصر التصنيفات الموجودة عندك فوق
  // أمثلة: '#categoryTabs a'  أو  '.cats a'  أو  '.tabs button'
  const CAT_LINKS = '#categoryTabs a'; // <-- عدّل هذا السطر فقط

  const gridSelector = '#coursesGridBlogs';
  const paginationSelector = '#paginationBlogs';
  const perPageSelector = '#perPageSelect';

  async function loadBlogs(url) {
    try {
      const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await resp.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');

      const newGrid = doc.querySelector(gridSelector);
      const newPagination = doc.querySelector(paginationSelector);
      const oldGrid = document.querySelector(gridSelector);
      const oldPagination = document.querySelector(paginationSelector);

      if (newGrid && oldGrid) oldGrid.replaceWith(newGrid);
      if (newPagination && oldPagination) oldPagination.replaceWith(newPagination);

      history.pushState({}, '', url);

      // بعد الاستبدال، نعيد ربط المستمعين لأنها بتروح مع DOM القديم
      attachHandlers();
    } catch (e) {
      console.error('AJAX load error:', e);
    }
  }

  function getPerPage() {
    const u = new URL(location.href);
    return u.searchParams.get('per_page') || (document.querySelector(perPageSelector)?.value || '9');
  }

  function buildUrl(mod = {}) {
    const u = new URL(location.href);
    Object.entries(mod).forEach(([k, v]) => {
      if (v === null || v === undefined || v === '') u.searchParams.delete(k);
      else u.searchParams.set(k, v);
    });
    if (!u.searchParams.get('per_page')) u.searchParams.set('per_page', getPerPage());
    // لما نبدّل تصنيف/عدد العناصر، رجّع للصفحة الأولى
    if (mod.category !== undefined || mod.per_page !== undefined) u.searchParams.delete('page');
    return u.toString();
  }

  function getCurrentPage() {
    const u = new URL(location.href);
    return parseInt(u.searchParams.get('page') || '1', 10) || 1;
  }
  function getLastPageFromDom() {
    const nums = [...document.querySelectorAll('#pagesNumbers button[data-page]')]
      .map(b => parseInt(b.dataset.page, 10))
      .filter(n => !isNaN(n));
    return nums.length ? Math.max(...nums) : getCurrentPage();
  }

  function attachHandlers() {
    // 1) روابط/أزرار التصنيفات المحددة بالسيليكتور
    document.querySelectorAll(CAT_LINKS).forEach(el => {
      el.removeEventListener('click', el.__catHandler__);
      el.__catHandler__ = (e) => {
        e.preventDefault();
        // أولاً جرّب نقرأ slug من href إن وجد ?category=...
        const href = el.getAttribute('href') || '';
        let slug = null;
        if (/[?&]category=/.test(href)) {
          const u = new URL(href, location.origin);
          slug = u.searchParams.get('category');
        }
        // لو ما فيه، جرّب data-attrs (إن كانت موجودة أصلاً)
        slug = slug || el.dataset.category || el.dataset.slug;
        if (!slug) {
          // آخر حل: خُذ النص كما هو وحوّلو slug بسيط (بدون ما نعدّل HTML)
          const name = (el.textContent || '').trim();
          if (!name) return;
          slug = name
            .normalize('NFKD')
            .replace(/[\u0300-\u036f]/g,'') // إزالة تشكيل/ديacritics
            .replace(/\s+/g,'-')
            .replace(/[^\w\-]+/g,'')
            .toLowerCase();
        }
        const url = buildUrl({ category: slug, per_page: getPerPage() });
        loadBlogs(url);
      };
      el.addEventListener('click', el.__catHandler__);
    });

    // 2) تغيير PER PAGE
    const perSel = document.querySelector(perPageSelector);
    if (perSel) {
      perSel.removeEventListener('change', perSel.__ppHandler__);
      perSel.__ppHandler__ = () => {
        const url = buildUrl({ per_page: perSel.value });
        loadBlogs(url);
      };
      perSel.addEventListener('change', perSel.__ppHandler__);
    }

    // 3) أزرار الباجينيشن (الأزرار اللي أنت عاملها بـ data-page)
    document.querySelectorAll('#paginationBlogs [data-page]').forEach(btn => {
      btn.removeEventListener('click', btn.__pgHandler__);
      btn.__pgHandler__ = (e) => {
        e.preventDefault();
        const val = (btn.getAttribute('data-page') || '').toLowerCase();
        const cur = getCurrentPage();
        const last = getLastPageFromDom();
        let next = cur;
        if (val === 'prev') next = Math.max(1, cur - 1);
        else if (val === 'next') next = Math.min(last, cur + 1);
        else {
          const n = parseInt(val, 10);
          if (!isNaN(n)) next = n;
        }
        if (next !== cur) {
          const u = buildUrl({ page: next, per_page: getPerPage() });
          loadBlogs(u);
        }
      };
      btn.addEventListener('click', btn.__pgHandler__);
    });
  }

  // أول تشغيل
  attachHandlers();

  // دعم الرجوع/التقدّم
  window.addEventListener('popstate', () => loadBlogs(location.href));
})();
</script>

</x-front-layout>