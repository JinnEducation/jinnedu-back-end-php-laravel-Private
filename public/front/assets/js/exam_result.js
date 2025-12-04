$(document).ready(function () {
  // Clear all exam data for this exam from localStorage when viewing success page
  const examSection = $("section[data-exam-id]").first();
  const examId = examSection.data("exam-id");
  if (examId) {
    const keys = Object.keys(localStorage);
    keys.forEach((key) => {
      if (key.startsWith(`exam_${examId}_`)) {
        localStorage.removeItem(key);
      }
    });
  }
  // Handle registration disclaimer checkbox
  $("#risk-acknowledgement").on("change", function () {
    const isChecked = $(this).is(":checked");
    const btn = $("#registration-risk-btn");

    if (isChecked) {
      btn
        .prop("disabled", false)
        .removeClass("opacity-50 cursor-not-allowed")
        .addClass("hover:bg-primary-700 hover:scale-105");
    } else {
      btn
        .prop("disabled", true)
        .addClass("opacity-50 cursor-not-allowed")
        .removeClass("hover:bg-primary-700 hover:scale-105");
    }
  });
});
