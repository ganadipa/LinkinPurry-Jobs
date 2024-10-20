import { toast } from "../toast.js";

function validateEmail(email) {
  const re = /\S+@\S+\.\S+/;
  return re.test(email);
}

document.addEventListener("DOMContentLoaded", function () {
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

  // For form submission
  const loginForm = document.getElementById("login-form");
  loginForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    const submitButton = this.querySelector("button[type=submit]");
    submitButton.disabled = true;
    submitButton.textContent = "Loading...";

    const responseContainer = document.getElementById("response-container");

    fetch("/api/login", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          window.location.href = "/";
          responseContainer.classList.add("hidden");
        } else {
          submitButton.disabled = false;
          submitButton.textContent = "Sign in";

          // Display error message
          responseContainer.innerText = `${data.message}`;
          responseContainer.classList.remove("hidden");

          // Display toast
          toast("error", data.message);
        }
      });
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
