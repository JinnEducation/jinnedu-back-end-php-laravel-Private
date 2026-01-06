// Course Detail Page - Interactive Elements for course.html
$(document).ready(function () {
  // ==================== FAQ Accordion ====================
  $(".faq-question").on("click", function () {
    const $faqItem = $(this).closest(".faq-item");
    const $answer = $faqItem.find(".faq-answer");
    const $icon = $(this).find("i");

    // Toggle current answer with smooth animation
    $answer.slideToggle(300, function () {
      // Callback after animation completes
    });

    // Rotate icon
    $icon.toggleClass("rotate-180");
  });

  // ==================== Course Content Accordions ====================
  $(".accordion-header").on("click", function () {
    const $accordionBody = $(this).next(".accordion-body");
    const $icon = $(this).find(".icon");

    // Toggle accordion body with callback
    $accordionBody.slideToggle(200, function () {
      // After animation, update icon based on visibility
      if ($accordionBody.is(":visible")) {
        // Open - show minus icon
        $icon.html(
          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>'
        );
        $icon.addClass("rotate-180");
      } else {
        // Closed - show plus icon
        $icon.html(
          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
        );
        $icon.removeClass("rotate-180");
      }
    });
  });

  // ==================== Course Tabs ====================
  $(".course-tab").on("click", function (e) {
    e.preventDefault();

    // Remove active classes from all tabs
    $(".course-tab")
      .removeClass("font-bold text-white bg-primary border-primary")
      .addClass("bg-white text-black border-[#CAC6C6]");

    // Add active classes to clicked tab
    $(this)
      .removeClass("bg-white text-black border-[#CAC6C6]")
      .addClass("bg-primary text-white border-primary font-bold");

    // Get target section
    const target = $(this).data("target");
    if (target) {
      const $targetSection = $(target);
      if ($targetSection.length) {
        // Smooth scroll to target section
        $("html, body").animate(
          {
            scrollTop: $targetSection.offset().top - 120,
          },
          500
        );
      }
    }
  });

  // ==================== Smooth Scrolling ====================
  $('a[href^="#"]').on("click", function (e) {
    const target = $(this.hash);
    if (target.length) {
      e.preventDefault();
      $("html, body").animate(
        {
          scrollTop: target.offset().top - 120,
        },
        500
      );
    }
  });

  // ==================== View Details Buttons ====================
  $('.recommendation-card button, a[href*="view-details"]').on(
    "click",
    function (e) {
      // Optional: Add analytics tracking
      console.log("View details clicked for recommendation");
    }
  );

  // ==================== Message Tutor / View Profile ====================
  $('button:contains("Message tutor")').on("click", function (e) {
    e.preventDefault();
    alert(
      "Message feature coming soon!\nYou will be able to message the tutor directly."
    );
    console.log("Message tutor clicked");
  });

  $('button:contains("View profile")').on("click", function (e) {
    e.preventDefault();
    alert("Tutor profile feature coming soon!");
    console.log("View profile clicked");
  });

  // ==================== Add rotate-180 Tailwind class support ====================
  // Ensure Tailwind's rotate-180 is available
  if (!$("style#dynamic-rotate").length) {
    $("head").append(
      '<style id="dynamic-rotate">.rotate-180 { transform: rotate(180deg); transition: transform 0.3s ease; }</style>'
    );
  }

  // $(function(){
  //     $('#fav-btn').on('click', function(){
  //         $(this).toggleClass('selected');
  //     });
  // });
  $("#fav-btn").on("click", function () {
    $(this).toggleClass("selected");
    const $notFaved = $(this).find(".not-faved");
    const $faved = $(this).find(".faved");
    if ($(this).hasClass("selected")) {
      $notFaved.addClass("!hidden");
      $faved.removeClass("!hidden");
    } else {
      $notFaved.removeClass("!hidden");
      $faved.addClass("!hidden");
    }
  });
});

// Courses Filtering
$(document).ready(function () {
  let currentDisplayed = 4;
  // Load More Function
  $("#loadMoreBtn").on("click", function () {
    let hiddenCards = $(".course-card")
      .filter(function () {
        return $(this).css("display") === "none";
      })
      .slice(0, 4);

    if (hiddenCards.length > 0) {
      hiddenCards.each(function (index) {
        $(this)
          .delay(index * 80)
          .fadeIn(300)
          .css({
            transform: "translateY(30px)",
            opacity: "0",
          })
          .animate(
            {
              opacity: "1",
            },
            {
              duration: 300,
              step: function (now, fx) {
                if (fx.prop === "opacity") {
                  $(this).css(
                    "transform",
                    "translateY(" + (30 - 30 * now) + "px)"
                  );
                }
              },
              complete: function () {
                $(this).css("transform", "translateY(0)");

                if (index === hiddenCards.length - 1) {
                  updateLoadMoreButton();
                }
              },
            }
          );
      });
      currentDisplayed += hiddenCards.length;
    }

    if (hiddenCards.length === 0 || $(".course-card").length <= 4) {
      $("#loadMoreBtn").fadeOut(200);
    } else {
      $("#loadMoreBtn").fadeIn(200);
    }
  });
});
