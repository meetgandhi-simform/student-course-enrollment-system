function toggleDropdown() {
  document.getElementById("dropdownMenu").classList.toggle("show");
}

window.onclick = function (e) {
  if (!e.target.matches("button")) {
    let dropdown = document.getElementById("dropdownMenu");
    if (dropdown.classList.contains("show")) {
      dropdown.classList.remove("show");
    }
  }
};
