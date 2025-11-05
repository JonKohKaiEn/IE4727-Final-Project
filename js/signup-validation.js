document.getElementById("signupForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const errors = [];

    const name = document.getElementById("fullName").value.trim();
    const email = document.getElementById("email").value.trim();
    const birthday = document.getElementById("birthday").value;
    const phone = document.getElementById("phone").value.trim();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("createPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const agreeTnC = document.getElementById("agreeTnC").checked;
    const marketingOptIn = document.getElementById("marketingOptIn").checked;

    const nameRegex = /^[A-Za-z\s\-\/]+$/;
    const emailRegex = /^[A-Za-z0-9_]+@[A-Za-z0-9]+\.[A-Za-z]+$/;
    const phoneRegex = /^[0-9]+$/;
    const usernameRegex = /^[A-Za-z0-9]+$/;
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!nameRegex.test(name)) errors.push("Full name contains invalid characters.");
    if (!emailRegex.test(email)) errors.push("Invalid email format.");
    if (!phoneRegex.test(phone)) errors.push("Phone number must be numeric.");
    if (!usernameRegex.test(username)) errors.push("Username must be alphanumeric only.");
    if (!passwordRegex.test(password)) errors.push("Password must be at least 8 chars with letters, numbers, and symbols.");
    if (password !== confirmPassword) errors.push("Passwords do not match.");
    if (!agreeTnC) errors.push("You must agree to the Terms & Conditions.");

    if (errors.length > 0) {
        showPopup(errors.join("\n"), false);
        return;
    }

    const formData = new FormData();
    formData.append("full_name", name);
    formData.append("email", email);
    formData.append("birthday", birthday);
    formData.append("phone", phone);
    formData.append("username", username);
    formData.append("password", password);
    formData.append("marketing_opt_in", marketingOptIn ? 1 : 0);

    const response = await fetch("../api/signup.php", { method: "POST", body: formData });
    const result = await response.text();

    if (result === "success") {
        showPopup("Thanks for joining us!", true);
        document.getElementById("signupForm").reset();
    } else {
        showPopup(result, false);
    }
});

function showPopup(message, success) {
    const popup = document.getElementById("popup");
    const msg = document.getElementById("popupMessage");
    msg.textContent = message;
    popup.classList.remove("hidden", "error");
    if (!success) popup.classList.add("error");
    popup.classList.remove("hidden");
}
