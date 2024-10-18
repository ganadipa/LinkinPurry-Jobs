// Initialize Lucide icons
lucide.createIcons();

// Handle file uploads
function handleFileUpload(
  inputId,
  fileNameId,
  errorId,
  maxSize,
  acceptedTypes
) {
  const input = document.getElementById(inputId);
  const fileNameElement = document.getElementById(fileNameId);
  const errorElement = document.getElementById(errorId);

  input.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      if (file.size > maxSize) {
        errorElement.textContent = `File size exceeds ${
          maxSize / 1024 / 1024
        } MB limit.`;
        input.value = "";
        fileNameElement.textContent = "";
      } else if (acceptedTypes && !acceptedTypes.includes(file.type)) {
        errorElement.textContent = "Invalid file type.";
        input.value = "";
        fileNameElement.textContent = "";
      } else {
        fileNameElement.textContent = file.name;
        errorElement.textContent = "";
      }
    }
  });
}

handleFileUpload("cv-upload", "cv-file-name", "cv-error", 2 * 1024 * 1024, [
  "application/pdf",
]);
handleFileUpload(
  "video-upload",
  "video-file-name",
  "video-error",
  100 * 1024 * 1024
);

// Handle form submission
document
  .getElementById("application-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    const cv = document.getElementById("cv-upload").files[0];
    const video = document.getElementById("video-upload").files[0];

    if (!cv || !video) {
      alert("Please upload both CV and introduction video before submitting.");
      return;
    }

    // Here you would typically send the files to your server
    console.log("Submitting application:", { cv, video });
    alert("Application submitted successfully!");
  });
