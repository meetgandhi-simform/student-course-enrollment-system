$(document).ready(function () {
  // Define columns for DataTable
  const columns = [
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
      orderable: false,
      searchable: false,
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
  ];

  // Initialize DataTable using helper
  const adminTable = initializeDataTable(
    "#adminTable",
    "/course-management/api/admins/getAdmins.php",
    columns,
    { pageLength: 5 },
  );

  // ACTIVATE USER
  $("#adminTable tbody").on("click", ".active-btn", function () {
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
          adminTable.ajax.reload(null, false);
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

  // DELETE USER
  $("#adminTable tbody").on("click", ".delete-btn", function () {
    let userId = $(this).attr("data-id");

    if (!confirm("Delete this user?")) {
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
          adminTable.ajax.reload(null, false);
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
