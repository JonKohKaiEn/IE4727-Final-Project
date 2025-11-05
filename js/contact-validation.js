// Show/hide "If Others" input based on radio selection
document.querySelectorAll('input[name="purpose"]').forEach(radio => {
  radio.addEventListener('change', function() {
    const purposeOther = document.getElementById("purposeOther");
    if (this.value === "Others") {
      purposeOther.classList.add("show");
      purposeOther.required = true;
    } else {
      purposeOther.classList.remove("show");
      purposeOther.required = false;
      purposeOther.value = "";
    }
  });
});

// Initialize on page load
document.addEventListener("DOMContentLoaded", function() {
  const purposeOther = document.getElementById("purposeOther");
  const othersRadio = document.querySelector('input[name="purpose"][value="Others"]');
  if (othersRadio && !othersRadio.checked) {
    purposeOther.classList.remove("show");
  }
});

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
  popup.classList.remove("hidden", "error", "show");
  msg.innerText = message;
  if (!success) popup.classList.add("error");

  // Show popup with animation
  setTimeout(() => popup.classList.add("show"), 50);
  
  // Auto-hide after 3 seconds or on close button click
  const closeBtn = document.getElementById("popupClose");
  if (closeBtn) {
    closeBtn.onclick = () => {
      popup.classList.remove("show");
      setTimeout(() => popup.classList.add("hidden"), 300);
    };
  }

  setTimeout(() => {
    popup.classList.remove("show");
    setTimeout(() => popup.classList.add("hidden"), 300);
  }, 3000);
}
