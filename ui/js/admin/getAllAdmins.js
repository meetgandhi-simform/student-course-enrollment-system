$(document).ready(function () {
  const adminTable = $("#adminTable").DataTable({
    ajax: {
      url: "/course-management/api/admins/getAdmins.php",
      type: "GET",

      dataSrc: function (response) {
        if (response.status) {
          return response.data;
        }
        console.error(response.message);
        alert(response.message);
        return [];
      },

      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Something went wrong while fetching admins.");
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

        render: function (row) {
          if (row.isActive === "Active") {
            return `
                    <button class="delete-btn"
                            data-id="${row.id}">
                        Delete
                    </button>
                `;
          }

          return `
                  <button class="active-btn" data-id="${row.id}">
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

  // Activate button

  $("#adminTable tbody").on("click", ".active-btn", function () {
    let userId = $(this).attr("data-id");

    let confirmActivate = confirm(
      "Are you sure you want to activate this user?",
    );

    if (!confirmActivate) {
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

          $("#adminTable").DataTable().ajax.reload(null, false);
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

  // Delete Button

  $("#adminTable tbody").on("click", ".delete-btn", function () {
    let userId = $(this).attr("data-id");

    console.log(userId);

    let confirmDelete = confirm("Are you sure you want to delete this user?");

    if (!confirmDelete) {
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
