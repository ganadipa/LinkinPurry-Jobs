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

  const url = `/?toast_onload_type=${encodedType}&toast_onload_message=${encodedMessage}&toast_time=${
    Date.now() / 1000
  }`;

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

document.addEventListener("DOMContentLoaded", function () {
  const hamburger = document.getElementById("hamburger");
  const sidenav = document.getElementById("sidenav");
  const closeBtn = document.getElementById("closebtn");
  const overlay = document.getElementById("overlay");

  // Function to open the sidenav
  function openSidenav() {
    sidenav.style.width = "250px";
    overlay.style.display = "block";
  }

  // Function to close the sidenav
  function closeSidenav() {
    sidenav.style.width = "0";
    overlay.style.display = "none";
  }

  // Event listeners
  hamburger.addEventListener("click", openSidenav);
  closeBtn.addEventListener("click", closeSidenav);
  overlay.addEventListener("click", closeSidenav);

  // Optional: Close sidenav on ESC key press
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closeSidenav();
    }
  });
});

document.addEventListener("DOMContentLoaded", function (e) {
  const navLogout = document.querySelector("#logout-sm");

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
          // Location reload but remove the query string (remove the before stack url before)
          window.location.href = window.location.href.split("?")[0];
        }
      }
    };

    xhr.send();
  });
});

// server-timestamp
document.addEventListener("DOMContentLoaded", function () {
  // Select all elements with the 'server-timestamp' class
  const timeElements = document.querySelectorAll(".server-timestamp");

  timeElements.forEach(function (element) {
    const unixTimestamp = element.getAttribute("data-timestamp");

    if (unixTimestamp) {
      // Convert Unix timestamp from seconds to milliseconds
      const timestampInMs = parseInt(unixTimestamp, 10) * 1000;

      // Create a Date object
      const date = new Date(timestampInMs);

      // Check if the date is valid
      if (!isNaN(date.getTime())) {
        // Format options for displaying date and time
        const options = {
          year: "numeric",
          month: "long",
          day: "numeric",
          hour: "2-digit",
          minute: "2-digit",
          second: "2-digit",
          hour12: false, // Set to true for 12-hour format
        };

        // Convert to locale string based on client's locale
        const localTime = date.toLocaleString(undefined, options);

        // Display the local time
        element.textContent = localTime;
      } else {
        // Handle invalid date
        element.textContent = "Invalid Date";
      }
    } else {
      // Handle missing timestamp
      element.textContent = "No Date Provided";
    }
  });
});

// server-date
document.addEventListener("DOMContentLoaded", function () {
  // Select all elements with the 'server-date' class
  const dateElements = document.querySelectorAll(".server-date");

  dateElements.forEach(function (element) {
    const unixTimestamp = element.getAttribute("data-timestamp");

    if (unixTimestamp) {
      // Convert Unix timestamp from seconds to milliseconds
      const timestampInMs = parseInt(unixTimestamp, 10) * 1000;

      // Create a Date object
      const date = new Date(timestampInMs);

      // Check if the date is valid
      if (!isNaN(date.getTime())) {
        // Format options for displaying date
        const options = {
          year: "numeric",
          month: "long",
          day: "numeric",
        };

        // Convert to locale string based on client's locale
        const localDate = date.toLocaleString(undefined, options);

        // Display the local date
        element.textContent = localDate;
      } else {
        // Handle invalid date
        element.textContent = "Invalid Date";
      }
    } else {
      // Handle missing timestamp
      element.textContent = "No Date Provided";
    }
  });
});

// server-time
document.addEventListener("DOMContentLoaded", function () {
  // Select all elements with the 'server-time' class
  const timeElements = document.querySelectorAll(".server-time");

  timeElements.forEach(function (element) {
    const unixTimestamp = element.getAttribute("data-timestamp");

    if (unixTimestamp) {
      // Convert Unix timestamp from seconds to milliseconds
      const timestampInMs = parseInt(unixTimestamp, 10) * 1000;

      // Create a Date object
      const date = new Date(timestampInMs);

      // Check if the date is valid
      if (!isNaN(date.getTime())) {
        // Format options for displaying time
        const options = {
          hour: "2-digit",
          minute: "2-digit",
          second: "2-digit",
          hour12: false, // Set to true for 12-hour format
        };

        // Convert to locale string based on client's locale
        const localTime = date.toLocaleString(undefined, options);

        // Display the local time
        element.textContent = localTime;
      } else {
        // Handle invalid date
        element.textContent = "Invalid Time";
      }
    } else {
      // Handle missing timestamp
      element.textContent = "No Time Provided";
    }
  });
});
