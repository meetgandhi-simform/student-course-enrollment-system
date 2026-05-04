$(document).ready(function () {
  $("#loginForm").submit(function (e) {
    e.preventDefault();

    let formData = $(this).serialize();

    $.ajax({
      url: "/course-management/handlers/loginHandler.php",
      type: "POST",
      data: formData,
      dataType: "json",

      beforeSend: function () {
        $("button[type='submit']").prop("disabled", true).text("Logging in...");
      },

      success: function (response) {
        if (response.status) {
          window.location.href =
            "/course-management/ui/" +
            response.role +
            "/" +
            response.role +
            "Dashboard.php";
        } else {
          if (response.errors) {
            alert(response.errors.join("\n"));
          } else {
            alert(response.message);
          }

          $("#captcha_img").attr(
            "src",
            "/course-management/handlers/captchaHandler.php?" + Date.now(),
          );
        }
      },

      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Server error");
      },

      complete: function () {
        $("button[type='submit']").prop("disabled", false).text("Login");
      },
    });
  });
});
