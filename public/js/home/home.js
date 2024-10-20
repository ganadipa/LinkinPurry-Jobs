let page = 1;
const jobList = document.getElementById('job-list');
const loading = document.getElementById('loading');
const loadingAnimation = document.getElementById('loading-animation');
let isLoading = false;

const searchInput = document.getElementById('search-input');
const jobTypeFilters = document.querySelectorAll('input[name="job-type"]');
const locationTypeFilters = document.querySelectorAll('input[name="location-type"]');
const sortOrderFilters = document.querySelectorAll('input[name="sort-order"]');

function getSelectedRadioValue(radioButtons) {
    for (const radioButton of radioButtons) {
        if (radioButton.checked) {
            return radioButton.value;
        }
    }
    return "";
}

function createJobElement(job) {
    const jobElement = document.createElement('div');
    jobElement.className = 'job-card';
    jobElement.innerHTML = `
        <div class="job-info">
            <img src="https://placehold.co/50x50" alt="Company Logo" class="company-logo">
            <div>
                <h3>${job.title}</h3>
                <p>${job.company}</p>
                <p>${job.location}</p>
                <p class="draft-info">Draft • Created ${job.created}</p>
                <a href="#" class="complete-draft">Complete draft</a>
            </div>
        </div>
        <div class="job-actions">
            <button class="more-actions">•••</button>
            <div class="dropdown-menu">
                <a href="#" class="dropdown-item">
                    <i data-lucide="edit"></i>
                    Manage job
                </a>
                <a href="#" class="dropdown-item">
                    <i data-lucide="trash-2"></i>
                    Delete draft
                </a>
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
    loadingAnimation.style.display = 'block';
    jobList.style.opacity = '0.5';
}

function hideLoadingAnimation() {
    loadingAnimation.style.display = 'none';
    jobList.style.opacity = '1';
}

function loadJobs(isNewSearch = false) {
    if (isLoading) return;
    isLoading = true;
    
    if (isNewSearch) {
        showLoadingAnimation();
    } else {
        loading.style.display = 'block';
    }

    // Get filter and sort parameters
    const searchTerm = searchInput.value.toLowerCase();
    const jobType = getSelectedRadioValue(jobTypeFilters);
    const locationType = getSelectedRadioValue(locationTypeFilters);
    const sort = getSelectedRadioValue(sortOrderFilters);

    // Add a delay before loading the jobs (simulate loading)
    setTimeout(function() {
        const xhr = new XMLHttpRequest();
        const url = new URL('/api/jobs', window.location.origin);
        url.searchParams.append('page', page);
        url.searchParams.append('search', searchTerm);
        url.searchParams.append('jobType', jobType);
        url.searchParams.append('locationType', locationType);
        url.searchParams.append('sort', sort);

        xhr.open('GET', url.toString(), true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Response:', xhr.responseText); // Log the response for debugging
                try {
                    console.log('Parsing JSON:', xhr.responseText);
                    const jobs = JSON.parse(xhr.responseText);
                    console.log('Jobs:', jobs);

                    // Clear existing jobs if it's a new search
                    if (isNewSearch) {
                        jobList.innerHTML = '';
                    }

                    jobs.forEach(job => {
                        jobList.appendChild(createJobElement(job));
                    });

                    page++;
                    isLoading = false;
                    if (isNewSearch) {
                        hideLoadingAnimation();
                    } else {
                        loading.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.log('Raw response:', xhr.responseText);
                    isLoading = false;
                    if (isNewSearch) {
                        hideLoadingAnimation();
                    } else {
                        loading.style.display = 'none';
                    }
                }
            } else {
                console.error('Error loading jobs:', xhr.statusText);
                isLoading = false;
                if (isNewSearch) {
                    hideLoadingAnimation();
                } else {
                    loading.style.display = 'none';
                }
            }
        };

        xhr.onerror = function() {
            console.error('Network error:', xhr.statusText);
            isLoading = false;
            if (isNewSearch) {
                hideLoadingAnimation();
            } else {
                loading.style.display = 'none';
            }
        };

        xhr.send();
    }, 2000); // 2000ms (2 seconds) delay before loading the jobs
}

const debouncedLoadJobs = debounce(() => {
    page = 1; // Reset page number when search/filter/sort changes
    loadJobs(true); // Pass true to indicate it's a new search
}, 300);

// Event listeners for search, filter, and sort
searchInput.addEventListener('input', debouncedLoadJobs);

jobTypeFilters.forEach(radio => {
    radio.addEventListener('change', debouncedLoadJobs);
});

locationTypeFilters.forEach(radio => {
    radio.addEventListener('change', debouncedLoadJobs);
});

sortOrderFilters.forEach(radio => {
    radio.addEventListener('change', debouncedLoadJobs);
});

// Initial load
loadJobs();

function isBottomOfPage() {
    return window.innerHeight + window.scrollY >= document.body.offsetHeight - 100;
}

window.addEventListener('scroll', () => {
    if (isBottomOfPage()) {
        loadJobs();
    }
});

// Add event listeners for dropdown menus
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('more-actions')) {
        const dropdown = event.target.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    } else {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    }
});
