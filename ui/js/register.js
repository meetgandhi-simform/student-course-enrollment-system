$(document).ready(function () {
  $("#registerForm").submit(function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = form.serialize();

    $.ajax({
      url: "/course-management/auth/Register.php",
      type: "POST",
      data: formData,
      dataType: "json",

      beforeSend: function () {
        $("button[type='submit']")
          .prop("disabled", true)
          .text("Registering...");
      },

      success: function (response) {
        if (response.status) {
          alert(response.message);
          window.location.href = "/course-management/ui/login.php";
        } else {
          if (response.errors) {
            let errorText = Object.values(response.errors).join("\n");
            alert(errorText);
          } else {
            alert(response.message);
          }
        }
      },

      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Server error! Check console.");
      },

      complete: function () {
        $("button[type='submit']").prop("disabled", false).text("Register");
      },
    });
  });
});
