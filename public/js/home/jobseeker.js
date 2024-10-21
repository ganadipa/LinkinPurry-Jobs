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
  jobElement.href = `/job/${job.id}`;
  jobElement.className = "job-card";
  jobElement.innerHTML = `
        <div class="job-info" id='job-${job.id}'>
            <img src="https://placehold.co/50x50" alt="Company Logo" class="company-logo">
            <div>
                <h3>${job.title}</h3>
                <p>${job.company}</p>
                <p>${job.location}</p>
                <p class="draft-info">Draft • Created ${job.created}</p>
                <a href="#" class="complete-draft">Complete draft</a>
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
            jobList.appendChild(document.createTextNode("No more jobs found"));
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
  }, 1000); // 2000ms (2 seconds) delay before loading the jobs
}

const debouncedLoadJobs = debounce(() => {
  page = 1; // Reset page number when search/filter/sort changes

  // submit the form
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
