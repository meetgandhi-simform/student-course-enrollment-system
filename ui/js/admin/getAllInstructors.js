$(document).ready(function () {
  const instructorTable = $("#instructorTable").DataTable({
    ajax: {
      url: "/course-management/api/admins/getInstructors.php",
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
      { data: "name" },
      { data: "email" },
      { data: "phone" },
      { data: "role" },
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
          if (row.isActive === "Active") {
            return `
                    <button class="delete-btn"
                            data-id="${row.id}">
                        Delete
                    </button>
                    `;
          }

          return `
                <button class="active-btn"
                        data-id="${row.id}">
                    Activate User
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

  $("#instructorTable tbody").on("click", ".delete-btn", function () {
    let userId = $(this).attr("data-id");

    if (!confirm("Are you sure?")) {
      return;
    }

    $.ajax({
      url: "/course-management/auth/Delete.php",

      type: "POST",

      dataType: "json",

      data: {
        id: userId,
      },

      success: function (response) {
        if (response.status) {
          alert(response.message);

          instructorTable.ajax.reload(null, false);
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

  $("#instructorTable tbody").on("click", ".active-btn", function () {
    let userId = $(this).attr("data-id");

    if (!confirm("Activate this user?")) {
      return;
    }

    $.ajax({
      url: "/course-management/handlers/adminInstructorHandlers/activeUserHandler.php",

      type: "POST",

      dataType: "json",

      data: {
        id: userId,
      },

      success: function (response) {
        if (response.status) {
          alert(response.message);

          instructorTable.ajax.reload(null, false);
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
