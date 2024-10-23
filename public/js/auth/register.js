import { toast } from "../toast.js";

function nextStep(currentStep, nextStep) {
  if (validateStep(currentStep)) {
    document.getElementById(`step${currentStep}`).classList.remove("active");
    document.getElementById(`step${nextStep}`).classList.add("active");
  }
}

window.nextStep = nextStep;

function prevStep(currentStep, prevStep) {
  document.getElementById(`step${currentStep}`).classList.remove("active");
  document.getElementById(`step${prevStep}`).classList.add("active");
}

window.prevStep = prevStep;

function validateStep(step) {
  const currentStep = document.getElementById(`step${step}`);
  const inputs = currentStep.querySelectorAll("input, select, textarea");
  let isValid = true;

  inputs.forEach((input) => {
    const error = validateField(input);
    if (error) {
      showError(input, error);
      isValid = false;
    } else {
      clearError(input);
    }
  });

  return isValid;
}

function validateField(input) {
  const value = input.value.trim();
  switch (input.id) {
    case "name":
      return value === "" ? "Name is required" : "";
    case "email":
      return !isValidEmail(value) ? "Please enter a valid email address" : "";
    case "password":
      return value.length < 6
        ? "Password must be at least 6 characters long"
        : "";
    case "confirmPassword":
      return value !== document.getElementById("password").value
        ? "Passwords do not match"
        : "";
    default:
      return "";
  }
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

window.validateStep = validateStep;
window.validateField = validateField;
window.showError = showError;
window.clearError = clearError;

function isValidEmail(email) {
  // simple email validation
  const re = /\S+@\S+\.\S+/;
  return re.test(String(email).toLowerCase());
}

window.isValidEmail = isValidEmail;

document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get("message");
  const type = urlParams.get("type");

  if (message && type) {
    toast(type, message);
  }

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

  const inputs = form.querySelectorAll("input, textarea");
  inputs.forEach((input) => {
    input.addEventListener("focusout", function () {
      const error = validateField(this);
      if (error) {
        showError(this, error);
      } else {
        clearError(this);
      }
    });
  });
});
