export function toast(type, message) {
  const container = document.getElementById("toast-container");
  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;

  const iconName = getIconName(type);

  // prevent XSS, convert special characters to HTML entities
  message = message
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");

  type = type
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");

  toast.innerHTML = `
        <i class="toast-icon" data-lucide="${iconName}"></i>
        <div class="toast-content">
            <div class="toast-title">${capitalizeFirstLetter(type)}</div>
            <div class="toast-message">${decodeURIComponent(message)}</div>
        </div>
    `;

  container.appendChild(toast);
  lucide.createIcons();

  setTimeout(() => {
    toast.style.animation = "fadeOut 0.3s ease-out forwards";
    setTimeout(() => {
      container.removeChild(toast);
    }, 300);
  }, 3000);
}

function getIconName(type) {
  switch (type) {
    case "success":
      return "check-circle";
    case "error":
      return "x-circle";
    case "warning":
      return "alert-triangle";
    case "info":
      return "info";
    default:
      return "bell";
  }
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

document.addEventListener("DOMContentLoaded", function () {
  const toastContainer = document.getElementById("toast-container");
  const data_toast_onload_type = toastContainer.getAttribute(
    "data-toast-onload-type"
  );
  const data_toast_onload_message = toastContainer.getAttribute(
    "data-toast-onload-message"
  );
  const data_toast_time = toastContainer.getAttribute("data-toast-time");
  // if data toast time not integer, then set it to null
  if (data_toast_time && isNaN(parseInt(data_toast_time))) {
    data_toast_time = null;
  }

  if (
    !data_toast_time ||
    !data_toast_onload_type ||
    !data_toast_onload_message
  ) {
    return;
  }

  // data toast time current time in second
  // if 1s has passed since data toast time, then show the toast
  // remember, in seconds
  if (Math.floor(Date.now() / 1000) - data_toast_time <= 1) {
    toast(data_toast_onload_type, data_toast_onload_message);
  }
});
