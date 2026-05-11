$(document).ready(function () {
  // Define columns for DataTable
  const columns = [
    { data: "id" },
    { data: "course_name" },
    {
      data: null,
      render: function (data, type, row) {
        return `${row.avail_seats} / ${row.max_seats}`;
      },
    },
  ];

  // Initialize DataTable using helper
  initializeDataTable(
    "#courseTable",
    "/course-management/api/instructor/getCourses.php",
    columns,
    {
      pageLength: 5,
      language: {
        emptyTable: "No courses found",
        loadingRecords: "Loading courses...",
      },
    },
  );
});
