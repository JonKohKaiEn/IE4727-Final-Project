function validateAdmin() {
  const user = document.getElementById("username").value.trim();
  const pass = document.getElementById("password").value.trim();

  if (user === "" || pass === "") {
    alert("Please enter both username and password.");
    return;
  }

  if (user !== "admin" && pass !== "admin") {
    alert("Invalid username and password.");
  } else if (user !== "admin") {
    alert("Incorrect username.");
  } else if (pass !== "admin") {
    alert("Incorrect password.");
  } else {
    alert("Login successful!");
    window.location.href = "dashboard.php";
  }
}
