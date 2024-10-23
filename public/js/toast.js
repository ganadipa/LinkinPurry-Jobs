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
            <div class="toast-message">${message}</div>
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
