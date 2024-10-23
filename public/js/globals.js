export function escapeHTML(str) {
  return str
    .toString()
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#x27;")
    .replace(/\//g, "&#x2F;");
}

export function redirectToRootWithToast(type, message) {
  const encodedType = encodeURIComponent(type);
  const encodedMessage = encodeURIComponent(message);

  const url = `/?toast_onload_type=${encodedType}&toast_onload_message=${encodedMessage}`;

  window.location.href = url;
}

document.addEventListener("DOMContentLoaded", function (e) {
  const navLogout = document.querySelector("#logout");

  if (!navLogout) {
    return;
  }
  // xmlhttprequest
  navLogout.addEventListener("click", function (e) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/api/logout", true);

    xhr.onload = function () {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.status === "success") {
          location.reload();
        }
      }
    };

    xhr.send();
  });
});
