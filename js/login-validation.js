let loginAttempts = 0;

document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const username = document.getElementById("loginUsername").value.trim();
    const password = document.getElementById("loginPassword").value;

    const errors = [];
    if (!username) errors.push("Username is required.");
    if (!password) errors.push("Password is required.");

    if (errors.length > 0) {
        showPopup(errors.join("\n"), false);
        return;
    }

    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    const response = await fetch("../api/login.php", { method: "POST", body: formData });
    const result = await response.text();
    handleLoginResponse(result);
});

function handleLoginResponse(result) {
    if (result === "success") {
        showPopup("Welcome back!", true);
        loginAttempts = 0;
    } else if (result === "locked") {
        showPopup("Too many failed attempts. Please try again later.", false);
        document.getElementById("loginUsername").disabled = true;
        document.getElementById("loginPassword").disabled = true;
    } else {
        loginAttempts++;
        showPopup("Invalid username or password.", false);
    }
}

function showPopup(message, success) {
    const popup = document.getElementById("popup");
    const msg = document.getElementById("popupMessage");
    msg.textContent = message;
    popup.classList.remove("hidden", "error");
    if (!success) popup.classList.add("error");
    popup.classList.remove("hidden");
}
