document.addEventListener("DOMContentLoaded", function () {
    const loginBtn = document.getElementById("loginBtn");
    const errorMsg = document.getElementById("error-msg");

    loginBtn.addEventListener("click", function () {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        if (username === "" || password === "") {
            errorMsg.textContent = "Please enter both username and password.";
            return;
        }

        fetch("admin_auth.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "admin_dashboard.html";
            } else {
                errorMsg.textContent = "Invalid username or password.";
            }
        })
        .catch(error => {
            console.error("Error during login:", error);
            errorMsg.textContent = "An error occurred. Please try again.";
        });
    });
});
