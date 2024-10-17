function nextStep(currentStep, nextStep) {
  if (validateStep(currentStep)) {
    document.getElementById(`step${currentStep}`).classList.remove("active");
    document.getElementById(`step${nextStep}`).classList.add("active");
  }
}

function prevStep(currentStep, prevStep) {
  document.getElementById(`step${currentStep}`).classList.remove("active");
  document.getElementById(`step${prevStep}`).classList.add("active");
}

function validateStep(step) {
  const currentStep = document.getElementById(`step${step}`);
  const inputs = currentStep.querySelectorAll("input, select, textarea");
  let isValid = true;

  inputs.forEach((input) => {
    if (input.hasAttribute("required") && input.value.trim() === "") {
      showError(input, "This field is required");
      isValid = false;
    } else if (input.type === "email" && !isValidEmail(input.value)) {
      showError(input, "Please enter a valid email address");
      isValid = false;
    } else if (input.id === "password" && input.value.length < 6) {
      showError(input, "Password must be at least 6 characters long");
      isValid = false;
    } else if (
      input.id === "confirmPassword" &&
      input.value !== document.getElementById("password").value
    ) {
      showError(input, "Passwords do not match");
      isValid = false;
    } else {
      clearError(input);
    }
  });

  return isValid;
}

function showError(input, message) {
  clearError(input);
  const errorDiv = document.createElement("div");
  errorDiv.className = "error";
  errorDiv.textContent = message;
  input.parentNode.insertBefore(errorDiv, input.nextSibling);
}

function clearError(input) {
  const error = input.parentNode.querySelector(".error");
  if (error) {
    error.remove();
  }
}

function isValidEmail(email) {
  const re =
    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registrationForm");
  const userTypeSelect = document.getElementById("userType");
  const companyDetails = document.getElementById("companyDetails");
  const nameLabel = document.querySelector('label[for="name"]');

  userTypeSelect.addEventListener("change", function () {
    if (this.value === "company") {
      companyDetails.style.display = "block";
      nameLabel.textContent = "Company Name:";
    } else {
      companyDetails.style.display = "none";
      nameLabel.textContent = "Name:";
    }
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    if (validateStep(2)) {
      // Simulate form submission
      alert("Registration successful! Redirecting to home page...");
      // In a real application, you would send the data to a server here
      // and handle the response before redirecting
    }
  });
});
