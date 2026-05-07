$(document).ready(function () {
  const courseTable = $("#courseTable").DataTable({
    ajax: {
      url: "/course-management/api/admins/getCoursesWithInstructor.php",

      type: "GET",

      dataSrc: function (response) {
        if (response.status) {
          return response.data;
        }

        alert(response.message);

        return [];
      },

      error: function (xhr) {
        console.error(xhr.responseText);

        alert("Something went wrong.");
      },
    },

    columns: [
      { data: "id" },

      { data: "course_name" },

      {
        data: null,

        render: function (data, type, row) {
          return `
                        ${row.avail_seats} / ${row.max_seats}
                    `;
        },
      },

      { data: "instructor_id" },

      { data: "name" },

      {
        data: "isActive",

        render: function (data) {
          return `
                        <span class="status ${data.toLowerCase()}">
                            ${data}
                        </span>
                    `;
        },
      },

      {
        data: null,

        render: function (data, type, row) {
          return `
                        <button class="delete-btn"
                                data-course="${row.id}"
                                data-instructor="${row.instructor_id}">

                            Remove Instructor

                        </button>
                    `;
        },
      },
    ],

    paging: true,
    searching: true,
    ordering: true,
    info: true,
    pageLength: 5,
    responsive: true,
    processing: true,
  });

  $("#courseTable tbody").on("click", ".delete-btn", function () {
    let courseId = $(this).attr("data-course");

    let instructorId = $(this).attr("data-instructor");

    if (!confirm("Remove instructor from this course?")) {
      return;
    }

    $.ajax({
      url: "/course-management/handlers/adminHandlers/deleteCourseInstructor.php",

      type: "POST",

      dataType: "json",

      data: {
        course_id: courseId,
        instructor_id: instructorId,
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
