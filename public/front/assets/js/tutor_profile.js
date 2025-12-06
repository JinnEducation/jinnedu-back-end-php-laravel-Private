$(document).ready(function () {
  // ==========================================
  // TAB SWITCHING FUNCTIONALITY
  // ==========================================
  $(".tab-button").on("click", function () {
    const tabName = $(this).data("tab");

    // Don't do anything if clicking the active tab
    if ($(this).hasClass("active")) return;

    // Remove active class from all tabs and reset styles
    $(".tab-button").removeClass("active");
    $(".tab-button").removeClass("bg-primary text-white");
    $(".tab-button").addClass("text-black bg-transparent");

    // Add active class to clicked tab
    $(this).addClass("active");
    $(this).removeClass("text-black bg-transparent");
    $(this).addClass("bg-primary text-white hover:bg-primary hover:text-white");

    // Hide all tab contents with smooth fade out
    $(".tab-content").fadeOut(100, function () {
      // Show selected tab content with smooth fade in
      $(`#${tabName}-tab`).fadeIn(150);
    });
  });

  // ==========================================
  // SHOW MORE / SHOW LESS FUNCTIONALITY
  // ==========================================
  let isExpanded = false;

  $("#showMoreBtn").on("click", function () {
    if (!isExpanded) {
      // Expand
      $(".about-text-extra").slideDown(400);
      $(this).text("Show Less");
      isExpanded = true;
    } else {
      // Collapse
      $(".about-text-extra").slideUp(400);
      $(this).text("Show More");
      isExpanded = false;
    }
  });

  // ==========================================
  // SCHEDULE DATE NAVIGATION & DYNAMIC SCHEDULE
  // ==========================================
  
  // Get availability data from page
  const availabilityData = $('.bg-white.rounded-lg.p-6[data-availability]').data('availability') || [];

  // Day ID to name mapping
  const dayIdToName = {
    1: 'Sunday',
    2: 'Monday',
    3: 'Tuesday',
    4: 'Wednesday',
    5: 'Thursday',
    6: 'Friday',
    7: 'Saturday'
  };

  // Day names in order (Sunday to Saturday)
  const dayOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

  // Process availability data by day
  const scheduleByDay = {};
  if (availabilityData && availabilityData.length > 0) {
    availabilityData.forEach(slot => {
      // Handle both formats: {day: {id, name}} or {day_id, day_name}
      let dayId = 0;
      let dayName = '';
      
      if (slot.day && slot.day.id) {
        dayId = slot.day.id;
        dayName = slot.day.name || dayIdToName[dayId] || '';
      } else {
        dayId = slot.day_id || 0;
        dayName = slot.day_name || dayIdToName[dayId] || '';
      }
      
      if (!dayName) return;
      
      if (!scheduleByDay[dayName]) {
        scheduleByDay[dayName] = [];
      }
      
      scheduleByDay[dayName].push({
        from: slot.hour_from || '',
        to: slot.hour_to || ''
      });
    });
  }

  // Render schedule grid (general function)
  function renderScheduleGrid(gridSelector, dateSelector) {
    const scheduleGrid = $(gridSelector);
    scheduleGrid.empty();

    // Get current date for display
    const today = new Date();
    const currentDateStr = today.toLocaleDateString('en-US', {
      weekday: 'long',
      year: 'numeric',
      month: '2-digit',
      day: '2-digit'
    });
    if (dateSelector) {
      $(dateSelector).text(currentDateStr);
    }

    // Render each day in order
    dayOrder.forEach((dayName, index) => {
      const dayCol = $("<div>")
        .addClass("flex flex-col items-center")
        .css("min-width", "100px")
        .css("flex", "1");

      // Day name with top border
      const dayNameEl = $("<div>")
        .addClass(
          "text-sm text-primary font-medium mb-1 pt-2 border-t-2 border-primary w-full text-center"
        )
        .text(dayName);

      // Day number (current date + index)
      const dayDate = new Date(today);
      dayDate.setDate(today.getDate() - today.getDay() + index); // Start from Sunday
      const dayNumber = $("<div>")
        .addClass("text-xl font-bold text-primary mb-2")
        .text(dayDate.getDate());

      // Time slots container
      const timeSlotsContainer = $("<div>").addClass("w-1/2 space-y-1 mt-2");

      // Get time slots for this day
      const daySlots = scheduleByDay[dayName] || [];

      if (daySlots.length > 0) {
        // Add ellipsis at the top
        const ellipsis = $("<div>")
          .addClass("text-center py-1 text-sm border-b border-gray-300 font-normal")
          .text("..:.");
        timeSlotsContainer.append(ellipsis);

        // Add each time slot
        daySlots.forEach(slot => {
          // From time
          const fromSlot = $("<div>")
            .addClass("time-slot text-center py-1 text-sm border-b border-gray-400 font-semibold")
            .text(slot.from)
            .attr("data-day", dayName)
            .attr("data-time", slot.from);
          timeSlotsContainer.append(fromSlot);

          // To time
          const toSlot = $("<div>")
            .addClass("time-slot text-center py-1 text-sm border-b border-gray-400 font-semibold")
            .text(slot.to)
            .attr("data-day", dayName)
            .attr("data-time", slot.to);
          timeSlotsContainer.append(toSlot);
        });
      } else {
        // No availability
        const noSlot = $("<div>")
          .addClass("text-center py-1 text-sm border-b border-gray-300 font-normal text-gray-400")
          .text("-");
        timeSlotsContainer.append(noSlot);
      }

      dayCol.append(dayNameEl, dayNumber, timeSlotsContainer);
      scheduleGrid.append(dayCol);
    });
  }

  // Render schedule for tab
  function renderSchedule() {
    renderScheduleGrid("#scheduleGrid", "#weekDate");
  }

  // Render schedule for modal
  function renderScheduleModal() {
    renderScheduleGrid("#scheduleGridModal", "#weekDateModal");
  }

  // Navigation buttons - disabled (just for display)
  $("#prevWeek").on("click", function (e) {
    e.preventDefault();
    // Disabled - no functionality
  });

  $("#nextWeek").on("click", function (e) {
    e.preventDefault();
    // Disabled - no functionality
  });

  // Modal navigation buttons - disabled (just for display)
  $("#prevWeekModal").on("click", function (e) {
    e.preventDefault();
    // Disabled - no functionality
  });

  $("#nextWeekModal").on("click", function (e) {
    e.preventDefault();
    // Disabled - no functionality
  });

  // Initial render
  renderSchedule();

  // ==========================================
  // FULL SCHEDULE MODAL
  // ==========================================
  $("#viewFullScheduleBtn").on("click", function () {
    // Render schedule in modal
    renderScheduleModal();
    
    // Show modal
    $("#fullScheduleModal").removeClass("hidden").addClass("flex");
    $("#fullScheduleModal").hide().fadeIn(300);
    $("body").css("overflow", "hidden"); // Prevent background scrolling
  });

  $("#closeModalBtn").on("click", function () {
    $("#fullScheduleModal").fadeOut(300, function () {
      $(this).removeClass("flex").addClass("hidden");
    });
    $("body").css("overflow", "auto"); // Restore scrolling
  });

  // Close modal when clicking outside
  $("#fullScheduleModal").on("click", function (e) {
    if ($(e.target).is("#fullScheduleModal")) {
      $(this).fadeOut(300, function () {
        $(this).removeClass("flex").addClass("hidden");
      });
      $("body").css("overflow", "auto");
    }
  });

  // Close modal with Escape key
  $(document).on("keydown", function (e) {
    if (e.key === "Escape" && $("#fullScheduleModal").hasClass("flex")) {
      $("#fullScheduleModal").fadeOut(300, function () {
        $(this).removeClass("flex").addClass("hidden");
      });
      $("body").css("overflow", "auto");
    }
  });

  // ==========================================
  // SMOOTH SCROLL FOR ANCHOR LINKS
  // ==========================================
  $('a[href^="#"]').on("click", function (e) {
    const target = $(this.hash);
    if (target.length) {
      e.preventDefault();
      $("html, body").animate(
        {
          scrollTop: target.offset().top - 140, // Account for fixed header
        },
        600
      );
    }
  });

  // ==========================================
  // ADDITIONAL BUTTON CLICK HANDLERS
  // ==========================================
  // Handle action button clicks
  $(".action-btn").on("click", function () {
    const buttonText = $(this).text().trim();
    console.log(`Action button clicked: ${buttonText}`);
    // Add your specific action handling here
  });

  // ==========================================
  // VIDEO PLAYER INTERACTION
  // ==========================================
  $("video").on("click", function () {
    if (this.paused) {
      this.play();
      $(this).siblings(".bg-opacity-30").fadeOut(200);
    } else {
      this.pause();
      $(this).siblings(".bg-opacity-30").fadeIn(200);
    }
  });

  // ==========================================
  // RESPONSIVE HANDLING
  // ==========================================
  function handleResponsive() {
    const windowWidth = $(window).width();

    if (windowWidth < 1024) {
      // Mobile/Tablet view adjustments
      $(".sticky").removeClass("sticky");
    } else {
      // Desktop view
      $(".top-32").addClass("sticky");
    }
  }

  // Run on load
  handleResponsive();

  // Run on resize
  $(window).on("resize", function () {
    handleResponsive();
  });

  // ==========================================
  // INITIALIZATION MESSAGE
  // ==========================================
  console.log("Tutor Profile page initialized successfully!");
});
