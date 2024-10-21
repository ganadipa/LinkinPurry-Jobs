document.addEventListener("DOMContentLoaded", function (e) {
  const navLogout = document.querySelector("#logout");

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
