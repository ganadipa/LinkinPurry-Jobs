lucide.createIcons();

// Image carousel functionality

document.addEventListener("DOMContentLoaded", function () {
  let currentImageIndex = 0;
  const prevButton = document.getElementById("prevButton");
  const nextButton = document.getElementById("nextButton");
  const jobImages = document.querySelectorAll(".carousel-image");

  function updateCarouselImage() {
    jobImages.forEach((image, index) => {
      if (index === currentImageIndex) {
        image.classList.remove("hidden");
      } else {
        image.classList.add("hidden");
      }
    });

    return jobImages;
  }

  window.currentImageIndex = currentImageIndex;
  window.updateCarouselImage = updateCarouselImage;

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
  const statusOffer = document.querySelector("#jobStatus span");
  if (statusOffer.textContent.trim() === "Open") {
    statusOffer.classList.remove("red-tag");
  }

  updateCarouselImage();
});
