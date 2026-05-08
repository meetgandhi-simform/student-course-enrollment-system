$(document).ready(function () {
  $("#courseTable").DataTable({
    ajax: {
      url: "/course-management/api/instructor/getCourses.php",

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
    ],

    paging: true,
    searching: true,
    ordering: true,
    info: true,
    pageLength: 5,
    responsive: true,
    processing: true,

    language: {
      emptyTable: "No courses found",

      loadingRecords: "Loading courses...",
    },
  });
});
