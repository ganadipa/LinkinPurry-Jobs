lucide.createIcons();

// Simulated attachments (images) for the job posting
const jobImages = [
  "https://placehold.co/600x400",
  "https://placehold.co/600x400",
  "https://placehold.co/600x400",
];

// Handle apply button click
document.getElementById("applyButton").addEventListener("click", function () {
  alert("Application submitted!");
});

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
