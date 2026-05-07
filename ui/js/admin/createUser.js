function showForm(type, el) {
  document.getElementById("manual-form").style.display =
    type === "manual" ? "block" : "none";
  document.getElementById("csv-form").style.display =
    type === "csv" ? "block" : "none";

  const buttons = document.querySelectorAll(".toggle-container button");
  buttons.forEach((btn) => btn.classList.remove("active"));

  el.classList.add("active");
}
