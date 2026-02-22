// Courses: تابات مدفوع / مجاني + ترقيم وصفحات
$(document).ready(function () {
  let currentPage = 1;
  let perPage = parseInt($("#perPageSelect").val(), 10) || 12;
  let selectedType = "paid"; // paid | free

  function getFilteredCards() {
    return $(".course-card").filter(function () {
      var val = $(this).attr("data-is-free");
      var isFree = val === "1" || val === 1 || val === "true" || val === true;
      return selectedType === "free" ? isFree : !isFree;
    });
  }

  function setActiveTab() {
    $(".tab-type-btn")
      .removeClass("font-bold border-primary text-primary")
      .addClass("font-medium border-transparent");
    $(".tab-type-btn[data-type='" + selectedType + "']")
      .removeClass("font-medium border-transparent")
      .addClass("font-bold border-primary text-primary -mb-px");
  }

  function renderPagination(totalItems) {
    let totalPages = Math.ceil(totalItems / perPage) || 1;
    let container = $("#pagesNumbers").empty();

    for (let i = 1; i <= totalPages; i++) {
      container.append(
        '<button class="min-w-8 h-8 px-2 flex items-center justify-center text-sm font-medium rounded-full transition-all duration-200 cursor-pointer ' +
          (i === currentPage ? "bg-primary text-white shadow-sm" : "text-black hover:text-white hover:bg-primary") +
          '" data-page="' + i + '">' + i + "</button>"
      );
    }
  }

  function showPage() {
    // إخفاء كل الكروت أولاً ثم إظهار المفلتر فقط
    $(".course-card").hide();
    let cards = getFilteredCards();
    let start = (currentPage - 1) * perPage;
    let end = start + perPage;
    cards.slice(start, end).fadeIn(200);
    renderPagination(cards.length);
  }

  // نقر على تاب مدفوع / مجاني
  $(document).on("click", ".tab-type-btn", function () {
    selectedType = $(this).data("type");
    setActiveTab();
    currentPage = 1;
    showPage();
  });

  // ترقيم الصفحات
  $(document).on("click", "#paginationCourses button", function () {
    let page = $(this).data("page");
    let cards = getFilteredCards();
    let totalPages = Math.ceil(cards.length / perPage) || 1;
    if (page === "prev" && currentPage > 1) currentPage--;
    else if (page === "next" && currentPage < totalPages) currentPage++;
    else if (!isNaN(page)) currentPage = parseInt(page, 10);
    showPage();
  });

  $("#perPageSelect").on("change", function () {
    perPage = parseInt($(this).val(), 10);
    currentPage = 1;
    showPage();
  });

  // تشغيل أول مرة حسب التاب الافتراضي (مدفوع)
  showPage();
});

// مشاركة الرابط
$(document).ready(function () {
  let currentUrl = "";

  $(document).on("click", ".share-btn", function (e) {
    e.preventDefault();
    currentUrl = $(this).data("url");
    if (!currentUrl) return;
    $("#shareUrl").val(currentUrl);
    $(".copy-icon").removeClass("hidden");
    $(".check-icon").addClass("hidden");
    updateSocialLinks(currentUrl);
    $("#sharePopup").removeClass("hidden").css("display", "flex");
  });

  $(document).on("click", ".close-popup", function (e) {
    $("#sharePopup").addClass("hidden").css("display", "none");
  });

  $(document).on("click", ".popup-content", function (e) {
    e.stopPropagation();
  });

  $(document).on("click", "#copyBtn", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var url = $("#shareUrl").val();
    navigator.clipboard
      .writeText(url)
      .then(function () {
        $btn.find(".copy-icon").addClass("hidden");
        $btn.find(".check-icon").removeClass("hidden").addClass("copy-success");
        $btn.closest(".flex").removeClass("border-gray-200").addClass("border-green-400");
        setTimeout(function () {
          $btn.find(".copy-icon").removeClass("hidden");
          $btn.find(".check-icon").addClass("hidden").removeClass("copy-success");
          $btn.closest(".flex").removeClass("border-green-400").addClass("border-gray-200");
        }, 2000);
      })
      .catch(function (err) {
        console.error("فشل النسخ:", err);
        alert("فشل نسخ الرابط");
      });
  });

  function updateSocialLinks(url) {
    var encodedUrl = encodeURIComponent(url);
    $(".whatsapp-share").attr("href", "https://wa.me/?text=" + encodedUrl);
    $(".facebook-share").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodedUrl);
    $(".twitter-share").attr("href", "https://twitter.com/intent/tweet?url=" + encodedUrl);
    $(".telegram-share").attr("href", "https://t.me/share/url?url=" + encodedUrl);
  }

  $(document).on("keydown", function (e) {
    if (e.key === "Escape") {
      $("#sharePopup").addClass("hidden");
    }
  });
});
