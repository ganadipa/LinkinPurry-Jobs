let page = 1;
const jobList = document.getElementById('job-list');
const loading = document.getElementById('loading');
let isLoading = false;

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
                <a href="#" class="dropdown-item">Manage job</a>
                <a href="#" class="dropdown-item">Delete draft</a>
            </div>
        </div>
    `;
    return jobElement;
}

function loadJobs() {
    if (isLoading) return;
    isLoading = true;
    loading.style.display = 'block';

    // Add a delay before loading the jobs (simulate loading)
    setTimeout(function() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/api/jobs?page=${page}`, true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Response:', xhr.responseText); // Log the response for debugging
                try {
                    console.log('Parsing JSON:', xhr.responseText);
                    const jobs = JSON.parse(xhr.responseText);
                    console.log('Jobs:', jobs);

                    jobs.forEach(job => {
                        jobList.appendChild(createJobElement(job));
                    });

                    page++;
                    isLoading = false;
                    loading.style.display = 'none';
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.log('Raw response:', xhr.responseText);
                    isLoading = false;
                    loading.style.display = 'none';
                }
            } else {
                console.error('Error loading jobs:', xhr.statusText);
                isLoading = false;
                loading.style.display = 'none';
            }
        };

        xhr.onerror = function() {
            console.error('Network error:', xhr.statusText);
            isLoading = false;
            loading.style.display = 'none';
        };

        xhr.send();
    }, 2000); // 1000ms (1 second) delay before loading the jobs
}

function isBottomOfPage() {
    return window.innerHeight + window.scrollY >= document.body.offsetHeight - 100;
}

window.addEventListener('scroll', () => {
    if (isBottomOfPage()) {
        loadJobs();
    }
});

// Initial load
loadJobs();

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
