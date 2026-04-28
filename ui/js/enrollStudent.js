function findInstructors() {
  const courseId = document.getElementById("course_id").value;

  if (!courseId) return;

  fetch(`/course-management/handlers/getInstructor.php?course_id=${courseId}`)
    .then((res) => res.json())
    .then((response) => {
      const dropdown = document.getElementById("instructor_dropdown");
      dropdown.innerHTML = '<option value="">Select Instructor</option>';

      if (response.status) {
        response.data.forEach((row) => {
          const option = document.createElement("option");
          option.value = row.id;
          option.text = row.id + " - " + row.name;
          dropdown.appendChild(option);
        });
      }
    })
    .catch((err) => {
      console.error(err);
      alert("Something went wrong");
    });
}
