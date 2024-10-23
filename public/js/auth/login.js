import { toast } from "../toast.js";

function validateEmail(email) {
  const re = /\S+@\S+\.\S+/;
  return re.test(email);
}

document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get("message");
  const type = urlParams.get("type");

  if (message && type) {
    toast(type, message);
  }

  // Toggle password visibility
  const showPassword = document.querySelector(".show-password");
  showPassword.addEventListener("click", function () {
    const passwordInput = document.getElementById("password");
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      this.textContent = "Hide";
    } else {
      passwordInput.type = "password";
      this.textContent = "Show";
    }
  });

  // For client side validation (on focusout)P
  const emailInput = document.getElementById("email");
  emailInput.addEventListener("focusout", function () {
    const email = this.value;
    if (!validateEmail(email)) {
      toast("error", "Invalid email address");
    }
  });

  const passwordInput = document.getElementById("password");
  passwordInput.addEventListener("focusout", function () {
    const password = this.value;
    if (password.length == 0) {
      toast("error", "Password cannot be empty");
    }
  });
});
