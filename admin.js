document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("student-data");

    function loadStudentData() {
        fetch("fetch_students.php")
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = "";
                data.forEach(student => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${student.id}</td>
                        <td>${student.full_name}</td>
                        <td>${student.email}</td>
                        <td>${student.qualification}</td>
                        <td>${student.grad_year}</td>
                        <td>${student.score || "N/A"}</td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error("Error loading student data:", error));
    }

    loadStudentData();
});
