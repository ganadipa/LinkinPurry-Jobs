lucide.createIcons();

// Simulated attachments (images) for the job posting
const jobImages = [
  "https://placehold.co/400",
  "https://placehold.co/600x400",
  "https://placehold.co/600",
];

// Image carousel functionality
let currentImageIndex = 0;
const carouselImage = document.getElementById("carouselImage");
const prevButton = document.getElementById("prevButton");
const nextButton = document.getElementById("nextButton");

function updateCarouselImage() {
  carouselImage.src = jobImages[currentImageIndex];
}

prevButton.addEventListener("click", () => {
  currentImageIndex =
    (currentImageIndex - 1 + jobImages.length) % jobImages.length;
  updateCarouselImage();
});

nextButton.addEventListener("click", () => {
  currentImageIndex = (currentImageIndex + 1) % jobImages.length;
  updateCarouselImage();
});

// Initialize carousel
updateCarouselImage();

document.addEventListener("DOMContentLoaded", function () {
  const statusOffer = document.querySelector("#jobStatus span");
  if (statusOffer.textContent.trim() === "Open") {
    statusOffer.classList.remove("red-tag");
  }
});

const statusButton = document.querySelector("#statusButton");
const statusOffer = document.querySelector("#jobStatus span");

statusButton.addEventListener("click", function (e) {
  e.preventDefault();
  
  const path = window.location.href;
  const jobId = path.split("/").pop();
  console.log(jobId);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", `/job/${jobId}/togglestatus`, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Reload
      window.location.reload();
    }
  };
  
  xhr.send();
});

const deleteButton = document.querySelector("#deleteButton");

deleteButton.addEventListener("click", function (e) {
  e.preventDefault();

  const path = window.location.href;
  const jobId = path.split("/").pop();

  const xhr = new XMLHttpRequest();
  xhr.open("DELETE", `/job/${jobId}`, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Redirect
      window.location.href = "/";
    }
  };

  xhr.send();
});