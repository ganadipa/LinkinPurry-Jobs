let currentPage = 1;
let searchTimeout;

document.getElementById("searchInput").addEventListener("input", function () {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(fetchJobs, 300); // debounce: wait 300ms after typing stops
});

document.getElementById("jenisPekerjaan").addEventListener("change", fetchJobs);
document.getElementById("jenisLokasi").addEventListener("change", fetchJobs);

function fetchJobs() {
  const searchQuery = document.getElementById("searchInput").value;
  const jenisPekerjaan = document.getElementById("jenisPekerjaan").value;
  const jenisLokasi = document.getElementById("jenisLokasi").value;

  const xhr = new XMLHttpRequest();
  const url = `/jobs?page=${currentPage}&search=${encodeURIComponent(
    searchQuery
  )}&jenisPekerjaan=${encodeURIComponent(
    jenisPekerjaan
  )}&jenisLokasi=${encodeURIComponent(jenisLokasi)}`;

  xhr.open("GET", url, true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        try {
          const data = JSON.parse(xhr.responseText);
          renderJobs(data.jobs);
          renderPagination(data.totalPages);
        } catch (error) {
          console.error("Error parsing JSON:", error);
        }
      } else {
        console.error("Error:", xhr.statusText);
      }
    }
  };

  xhr.onerror = function () {
    console.error("Request failed");
  };

  xhr.send();
}

function renderJobs(jobs) {
  const jobList = document.getElementById("jobList");
  jobList.innerHTML = "";

  jobs.forEach((job) => {
    const jobItem = document.createElement("div");
    jobItem.classList.add("job-item");
    jobItem.innerHTML = `<h3>${job.posisi}</h3><p>${job.deskripsi}</p>`;
    jobList.appendChild(jobItem);
  });
}

function renderPagination(totalPages) {
  const pagination = document.getElementById("pagination");
  pagination.innerHTML = "";

  for (let page = 1; page <= totalPages; page++) {
    const pageLink = document.createElement("a");
    pageLink.href = "#";
    pageLink.textContent = page;
    pageLink.addEventListener("click", function (event) {
      event.preventDefault();
      currentPage = page;
      fetchJobs();
    });
    pagination.appendChild(pageLink);
  }
}

fetchJobs();
