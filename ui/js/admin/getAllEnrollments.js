$(document).ready(function () {
  const enrollmentTable = $("#enrollmentTable").DataTable({
    processing: true,

    serverSide: true,

    ajax: {
      url: "/course-management/api/admins/getEnrollments.php",
      type: "POST",
      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Something went wrong.");
      },
    },

    columns: [
      { data: "enrollment_id" },
      { data: "course_name" },
      { data: "student_id" },
      { data: "student_name" },
      { data: "instructor_id" },
      { data: "instructor_name" },
      {
        data: "enrollment_status",
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
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          if (row.enrollment_status === "Enrolled") {
            return `
              <button class="delete-btn"
                      data-id="${row.enrollment_id}">
                Cancel Enrollment
              </button>

              <button class="active-btn"
                      data-id="${row.enrollment_id}">
                Complete
              </button>
            `;
          }

          if (
            row.enrollment_status === "Cancelled" ||
            row.enrollment_status === "Completed"
          ) {
            return `
              <button class="reenroll-btn"
                      data-id="${row.enrollment_id}">
                Re-Enroll
              </button>
            `;
          }

          return "";
        },
      },
    ],

    pageLength: 5,

    responsive: true,
  });

  // CANCEL ENROLLMENT

  $("#enrollmentTable tbody").on("click", ".delete-btn", function () {
    let enrollmentId = $(this).attr("data-id");

    if (!confirm("Cancel this enrollment?")) {
      return;
    }

    $.ajax({
      url: "/course-management/handlers/adminHandlers/deleteEnrollment.php",

      type: "POST",

      dataType: "json",

      data: {
        id: enrollmentId,
      },

      success: function (response) {
        if (response.status) {
          alert(response.message);

          enrollmentTable.ajax.reload(null, false);
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

  // COMPLETE ENROLLMENT

  $("#enrollmentTable tbody").on("click", ".active-btn", function () {
    let enrollmentId = $(this).attr("data-id");

    if (!confirm("Complete this enrollment?")) {
      return;
    }

    $.ajax({
      url: "/course-management/handlers/adminHandlers/completeEnrollment.php",

      type: "POST",

      dataType: "json",

      data: {
        id: enrollmentId,
      },

      success: function (response) {
        if (response.status) {
          alert(response.message);

          enrollmentTable.ajax.reload(null, false);
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

  // RE-ENROLL

  $("#enrollmentTable tbody").on("click", ".reenroll-btn", function () {
    let enrollmentId = $(this).attr("data-id");

    if (!confirm("Re-enroll this student?")) {
      return;
    }

    $.ajax({
      url: "/course-management/handlers/adminHandlers/activeEnrollment.php",

      type: "POST",

      dataType: "json",

      data: {
        id: enrollmentId,
      },

      success: function (response) {
        if (response.status) {
          alert(response.message);

          enrollmentTable.ajax.reload(null, false);
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
