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

// Update status of the job offer
const statusJob = document.querySelector("#jobStatus span");
const statusButton = document.querySelector("#statusButton");