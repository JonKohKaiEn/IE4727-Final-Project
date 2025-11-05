document.getElementById("contactForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const errors = [];

  const name = document.getElementById("contactName").value.trim();
  const email = document.getElementById("contactEmail").value.trim();
  const purposeRadios = document.getElementsByName("purpose");
  let purpose = "";
  for (let r of purposeRadios) {
    if (r.checked) purpose = r.value;
  }
  const purposeOther = document.getElementById("purposeOther").value.trim();
  const message = document.getElementById("message").value.trim();

  const nameRegex = /^[A-Za-z\s\-\/]+$/;
  const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

  if (!nameRegex.test(name)) errors.push("• Full Name contains invalid characters.");
  if (!emailRegex.test(email)) errors.push("• Invalid Email format.");
  if (purpose === "Others" && purposeOther === "") errors.push("• Please specify the purpose of enquiry.");
  if (message === "") errors.push("• Message cannot be empty.");

  if (errors.length > 0) {
    showPopup(errors.join("\n"), false);
    return;
  }

  // Send form via PHP
  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("purpose", purpose === "Others" ? purposeOther : purpose);
  formData.append("message", message);

  const response = await fetch("../api/contact.php", { method: "POST", body: formData });
  const result = await response.text(); // PHP echoes plain text messages
  showPopup(result, true);
  document.getElementById("contactForm").reset();
});

function showPopup(message, success) {
  const popup = document.getElementById("popup");
  const msg = document.getElementById("popupMessage");
  popup.classList.remove("hidden", "error");
  msg.innerText = message;
  if (!success) popup.classList.add("error");

  // Show popup
  popup.classList.remove("hidden");

  document.getElementById("popupClose").onclick = () => {
    popup.classList.add("hidden");
  };
}
