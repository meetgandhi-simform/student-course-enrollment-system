const sidebar = document.querySelector(".sidebar");
const openBtn = document.getElementById("menuToggle");
const closeBtn = document.getElementById("closeSidebar");

openBtn.addEventListener("click", function () {
  sidebar.classList.add("active");
});

closeBtn.addEventListener("click", function () {
  sidebar.classList.remove("active");
});
