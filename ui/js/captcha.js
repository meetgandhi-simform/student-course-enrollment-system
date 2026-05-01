const btn = document.getElementById("btn");

btn.addEventListener("click", () => {
  const img = document.getElementById("captcha_img");
  img.src = "/course-management/handlers/captchaHandler.php?" + Date.now();
});
