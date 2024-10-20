let currentPage = 1;
let searchTimeout;

document.getElementById('searchInput').addEventListener('input', function() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(fetchJobs, 300); // debounce: wait 300ms after typing stops
});

document.getElementById('jenisPekerjaan').addEventListener('change', fetchJobs);
document.getElementById('jenisLokasi').addEventListener('change', fetchJobs);

function fetchJobs() {
  const searchQuery = document.getElementById('searchInput').value;
  const jenisPekerjaan = document.getElementById('jenisPekerjaan').value;
  const jenisLokasi = document.getElementById('jenisLokasi').value;

  fetch(`/jobs?page=${currentPage}&search=${searchQuery}&jenisPekerjaan=${jenisPekerjaan}&jenisLokasi=${jenisLokasi}`)
    .then(response => response.json())
    .then(data => {
      renderJobs(data.jobs);
      renderPagination(data.totalPages);
    })
    .catch(error => console.error('Error:', error));
}

function renderJobs(jobs) {
  const jobList = document.getElementById('jobList');
  jobList.innerHTML = ''; 

  jobs.forEach(job => {
    const jobItem = document.createElement('div');
    jobItem.classList.add('job-item');
    jobItem.innerHTML = `<h3>${job.posisi}</h3><p>${job.deskripsi}</p>`;
    jobList.appendChild(jobItem);
  });
}

function renderPagination(totalPages) {
  const pagination = document.getElementById('pagination');
  pagination.innerHTML = ''; 

  for (let page = 1; page <= totalPages; page++) {
    const pageLink = document.createElement('a');
    pageLink.href = '#';
    pageLink.textContent = page;
    pageLink.addEventListener('click', function(event) {
      event.preventDefault();
      currentPage = page;
      fetchJobs();
    });
    pagination.appendChild(pageLink);
  }
}

fetchJobs();
