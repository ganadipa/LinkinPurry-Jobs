// Simulasi data lowongan pekerjaan
let jobs = [
    { id: 1, title: "Software Engineer", status: "open", date: new Date(2024, 9, 15) },
    { id: 2, title: "Product Manager", status: "open", date: new Date(2024, 9, 14) },
    { id: 3, title: "Data Analyst", status: "closed", date: new Date(2024, 9, 13) },
    { id: 4, title: "UX Designer", status: "open", date: new Date(2024, 9, 12) },
    { id: 5, title: "Marketing Specialist", status: "open", date: new Date(2024, 9, 11) }
];

// Pengaturan halaman dan pagination
const itemsPerPage = 3;
let currentPage = 1;

// Fungsi untuk merender daftar lowongan pekerjaan
function renderJobs() {
    const jobList = document.querySelector('.job-list');
    jobList.innerHTML = '';

    const searchTerm = document.querySelector('.search-sort-filter input').value.toLowerCase();
    const sortValue = document.getElementById('sort-select').value;
    const filterValue = document.getElementById('filter-select').value;

    // Filter data berdasarkan pencarian dan status
    let filteredJobs = jobs.filter(job =>
        job.title.toLowerCase().includes(searchTerm) &&
        (filterValue === 'all' || job.status === filterValue)
    );

    // Sortir data berdasarkan nilai yang dipilih
    if (sortValue === 'newest') {
        filteredJobs.sort((a, b) => b.date - a.date);
    } else {
        filteredJobs.sort((a, b) => a.date - b.date);
    }

    // Pagination
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedJobs = filteredJobs.slice(startIndex, endIndex);

    // Render job items
    paginatedJobs.forEach(job => {
        const jobItem = document.createElement('div');
        jobItem.className = 'job-item';
        jobItem.innerHTML = `
            <span class="job-title" onclick="viewJobDetails(${job.id})">${job.title}</span>
            <div class="job-actions">
                <button onclick="editJob(${job.id})">Edit</button>
                <button onclick="deleteJob(${job.id})">Delete</button>
                <button onclick="toggleJobStatus(${job.id})">${job.status === 'open' ? 'Close' : 'Open'}</button>
            </div>
        `;
        jobList.appendChild(jobItem);
    });

    renderPagination(filteredJobs.length);
}

// Fungsi untuk merender tombol pagination
function renderPagination(totalItems) {
    const pagination = document.querySelector('.pagination');
    pagination.innerHTML = '';

    const pageCount = Math.ceil(totalItems / itemsPerPage);

    for (let i = 1; i <= pageCount; i++) {
        const button = document.createElement('button');
        button.innerText = i;
        button.classList.toggle('active', i === currentPage);
        button.onclick = () => {
            currentPage = i;
            renderJobs();
        };
        pagination.appendChild(button);
    }
}

// Fungsi untuk menambahkan lowongan pekerjaan baru
function addJob() {
    const newJobTitle = prompt("Enter new job title:");
    if (newJobTitle) {
        const newJob = {
            id: jobs.length + 1,
            title: newJobTitle,
            status: "open",
            date: new Date()
        };
        jobs.push(newJob);
        renderJobs();
    }
}

// Fungsi untuk mengedit lowongan pekerjaan
function editJob(id) {
    const job = jobs.find(j => j.id === id);
    const newTitle = prompt("Edit job title:", job.title);
    if (newTitle) {
        job.title = newTitle;
        renderJobs();
    }
}

// Fungsi untuk menghapus lowongan pekerjaan
function deleteJob(id) {
    if (confirm("Are you sure you want to delete this job?")) {
        jobs = jobs.filter(j => j.id !== id);
        renderJobs();
    }
}

// Fungsi untuk mengubah status lowongan (Open/Close)
function toggleJobStatus(id) {
    const job = jobs.find(j => j.id === id);
    job.status = job.status === 'open' ? 'closed' : 'open';
    renderJobs();
}

// Fungsi untuk melihat detail lowongan pekerjaan
function viewJobDetails(id) {
    const job = jobs.find(j => j.id === id);
    alert(`Job Details: \n${job.title}\nStatus: ${job.status}\nDate: ${job.date.toLocaleDateString()}`);
}

// Event listener untuk pencarian dan filter
document.querySelector('.search-sort-filter input').addEventListener('input', renderJobs);
document.getElementById('sort-select').addEventListener('change', renderJobs);
document.getElementById('filter-select').addEventListener('change', renderJobs);

// Event listener untuk tombol tambah lowongan baru