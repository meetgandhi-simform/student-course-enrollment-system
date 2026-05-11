$(document).ready(function () {
  $("#courseTable").DataTable({
    processing: true,
    serverSide: true,

    ajax: {
      url: "/course-management/api/instructor/getCourses.php",
      type: "POST",
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
    ],

    pageLength: 5,
    responsive: true,
    language: {
      emptyTable: "No courses found",
      loadingRecords: "Loading courses...",
    },
  });
});
