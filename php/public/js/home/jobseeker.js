import { escapeHTML } from "../globals.js";

let page = 1;
let isLoading = false;
let noMore = false;

const jobList = document.getElementById("job-list");
const loading = document.getElementById("loading");
const loadingAnimation = document.getElementById("loading-animation");
const searchInput = document.getElementById("search-input");
const jobTypeFilters = document.querySelectorAll('input[name="job-type[]"]');
const locationTypeFilters = document.querySelectorAll(
  'input[name="location-type[]"]'
);
const sortOrderFilters = document.querySelectorAll('input[name="sort-order"]');

function getSelectedRadioValue(radioButtons) {
  for (const radioButton of radioButtons) {
    if (radioButton.checked) {
      return radioButton.value;
    }
  }
  return "";
}

function getSelectedCheckboxValues(checkboxGroup) {
  const selectedValues = [];
  checkboxGroup.forEach((checkbox) => {
    if (checkbox.checked) {
      selectedValues.push(checkbox.value);
    }
  });
  return selectedValues;
}

function createJobElement(job) {
  const jobElement = document.createElement("a");
  const status = job.is_open ? "Open" : "Close";
  jobElement.href = `/job/${job.id}`;
  jobElement.className = "job-card";
  jobElement.innerHTML = `
        <div class="job-info" id='job-${job.id}'>
            <img src="/public/images/img-placeholder.svg" alt="Company Logo" class="company-logo">
            <div>
                <h3>${escapeHTML(job.title)}</h3>
                <p>${escapeHTML(job.company)}</p>
                <p>${escapeHTML(job.location)}</p>
                  <p class="draft-info">Job • Created ${new Date(
                    parseInt(job.created) * 1000
                  ).toLocaleDateString()}</p>
            </div>
            <div class="status-indicator">
                <div class="status status-${status}">${status}</div>
            </div>
        </div>
    `;

  // Replace the placeholder elements with Lucide icons
  lucide.createIcons(jobElement);

  return jobElement;
}

function debounce(func, delay) {
  let timeoutId;
  return function (...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func.apply(this, args), delay);
  };
}

function showLoadingAnimation() {
  loadingAnimation.style.display = "block";
  jobList.style.opacity = "0.5";
}

function hideLoadingAnimation() {
  loadingAnimation.style.display = "none";
  jobList.style.opacity = "1";
}

function loadJobs(isNewSearch = false) {
  if (isLoading) return;
  isLoading = true;

  if (isNewSearch) {
    showLoadingAnimation();
  } else {
    loading.style.display = "block";
  }

  // Get filter and sort parameters
  const searchTerm = searchInput.value.toLowerCase();
  const jobType = getSelectedCheckboxValues(jobTypeFilters);
  const locationType = getSelectedCheckboxValues(locationTypeFilters);
  const sort = getSelectedRadioValue(sortOrderFilters);

  // Add a delay before loading the jobs (simulate loading)
  setTimeout(function () {
    const xhr = new XMLHttpRequest();
    const url = new URL("/api/jobs", window.location.origin);
    url.searchParams.append("page", page);
    url.searchParams.append("q", searchTerm);
    url.searchParams.append("job-type[]", jobType);
    url.searchParams.append("location-type[]", locationType);
    url.searchParams.append("sort-order", sort);

    xhr.open("GET", url.toString(), true);

    xhr.onload = function () {
      if (xhr.status === 200) {
        try {
          const jobs = JSON.parse(xhr.responseText);

          // Clear existing jobs if it's a new search
          if (isNewSearch) {
            jobList.innerHTML = "";
          }

          jobs.forEach((job) => {
            jobList.appendChild(createJobElement(job));
          });

          if (jobs.length < 10) {
            const paragraph = document.createElement("p");
            paragraph.textContent = "No more jobs";
            paragraph.style.color = "red";
            paragraph.style.textAlign = "center";

            jobList.appendChild(paragraph);
            noMore = true;
          }

          page++;
          isLoading = false;
          if (isNewSearch) {
            hideLoadingAnimation();
          } else {
            loading.style.display = "none";
          }
        } catch (error) {
          console.error("Error parsing JSON:", error);
          console.log("Raw response:", xhr.responseText);
          isLoading = false;
          if (isNewSearch) {
            hideLoadingAnimation();
          } else {
            loading.style.display = "none";
          }
        }
      } else {
        console.error("Error loading jobs:", xhr.statusText);
        isLoading = false;
        if (isNewSearch) {
          hideLoadingAnimation();
        } else {
          loading.style.display = "none";
        }
      }
    };

    xhr.onerror = function () {
      console.error("Network error:", xhr.statusText);
      isLoading = false;
      if (isNewSearch) {
        hideLoadingAnimation();
      } else {
        loading.style.display = "none";
      }
    };

    xhr.send();
  }, 1000);
}

const debouncedLoadJobs = debounce(() => {
  page = 1;

  const submitButton = document.getElementById("apply-filters");
  submitButton.click();
}, 500);

// Initial load
loadJobs();

function isBottomOfPage() {
  return (
    window.innerHeight + window.scrollY >= document.body.offsetHeight - 100
  );
}

window.addEventListener("scroll", () => {
  // If no page again
  if (isBottomOfPage() && !noMore) {
    loadJobs();
  }
});

searchInput.addEventListener("input", debouncedLoadJobs);
