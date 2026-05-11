$(document).ready(function () {
  // Define columns for DataTable
  const columns = [
    { data: "id" },
    { data: "course_name" },
    { data: "duration_weeks" },
    { data: "avail_seats" },
    { data: "instructor_name" },
    {
      data: null,
      render: function (data, type, row) {
        return `
          <button class="active-btn"
                  data-course="${row.id}"
                  data-course-instructor="${row.course_instructor_id}">
              Enroll
          </button>
        `;
      },
    },
  ];

  // Initialize DataTable using helper
  const courseTable = initializeDataTable(
    "#courseTable",
    "/course-management/api/student/getCourses.php",
    columns,
    { pageLength: 5 },
  );

  $("#courseTable tbody").on("click", ".active-btn", function () {
    let courseId = $(this).attr("data-course");

    let courseInstructorId = $(this).attr("data-course-instructor");

    if (!confirm("Enroll in this course?")) {
      return;
    }

    $.ajax({
      url: "/course-management/handlers/studentHandlers/enrollHandler.php",

      type: "POST",

      dataType: "json",

      data: {
        course_id: courseId,
        course_instructor_id: courseInstructorId,
      },

      success: function (response) {
        if (response.status) {
          alert(response.message);

          courseTable.ajax.reload(null, false);
        } else {
          alert(response.message);
        }
      },

      error: function (xhr) {
        console.error(xhr.responseText);

        alert("Something went wrong.");
      },
    });
  });
});
