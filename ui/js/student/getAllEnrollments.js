$(document).ready(function () {
  const enrollmentTable = $("#enrollmentTable").DataTable({
    ajax: {
      url: "/course-management/api/student/getEnrollments.php",

      type: "POST",

      error: function (xhr) {
        console.error(xhr.responseText);

        alert("Something went wrong.");
      },
    },

    columns: [
      { data: "id" },

      { data: "name" },

      { data: "weeks" },

      { data: "instructor_id" },

      { data: "instructor_name" },

      {
        data: "status",

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
          if (row.status === "Enrolled") {
            return `
              <button class="delete-btn"
                      data-id="${row.enrollment_id}">
                  Cancel
              </button>

              <button class="complete-btn"
                      data-id="${row.enrollment_id}">
                  Complete
              </button>
            `;
          }

          if (row.status === "Cancelled") {
            return `
              <button class="active-btn"
                      data-id="${row.enrollment_id}">
                  Re-Enroll
              </button>
            `;
          }

          return "";
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

    serverSide: true,
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

  $("#enrollmentTable tbody").on("click", ".complete-btn", function () {
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

  $("#enrollmentTable tbody").on("click", ".active-btn", function () {
    let enrollmentId = $(this).attr("data-id");

    if (!confirm("Re-enroll this course?")) {
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
