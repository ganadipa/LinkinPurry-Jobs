import { redirectToRootWithToast } from "../globals.js";
import { toast } from "../toast.js";

// Initialize Quill editor
const quill = new Quill("#editor", {
  theme: "snow",
});

// Initialize lucide icons
document.addEventListener("DOMContentLoaded", function () {
  lucide.createIcons();
});

const dragDropArea = document.getElementById("drag-drop-area");
const fileInput = document.getElementById("file-input");
const imagePreviewContainer = document.getElementById(
  "image-preview-container"
);
const uploadInstructions = document.getElementById("upload-instructions");
const uploadButton = document.querySelector(".upload-button");
let files = [];

uploadButton.addEventListener("click", (e) => {
  e.stopPropagation();
  fileInput.click();
});

dragDropArea.addEventListener("click", (e) => {
  if (
    e.target === dragDropArea ||
    e.target === uploadInstructions ||
    uploadInstructions.contains(e.target)
  ) {
    fileInput.click();
  }
});

dragDropArea.addEventListener("dragover", (e) => {
  e.preventDefault();
  dragDropArea.classList.add("dragover");
});

dragDropArea.addEventListener("dragleave", () => {
  dragDropArea.classList.remove("dragover");
});

dragDropArea.addEventListener("drop", (e) => {
  e.preventDefault();
  dragDropArea.classList.remove("dragover");
  const droppedFiles = Array.from(e.dataTransfer.files).filter((file) =>
    file.type.startsWith("image/")
  );
  handleFiles(droppedFiles);
});

fileInput.addEventListener("change", (e) => {
  const selectedFiles = Array.from(e.target.files);
  handleFiles(selectedFiles);
});

function handleFiles(newFiles) {
  files = [...files, ...newFiles];
  updateImagePreviews();
}

function submitJobPosting(companyId) {
  console.log("Job Title:", document.getElementById("job-title").value);
  console.log("Description:", quill.root.innerHTML);
  console.log("Job Type:", document.getElementById("job-type").value);
  console.log("Location Type:", document.getElementById("location-type").value);

  const formData = new FormData();
  formData.append("company_id", companyId);
  formData.append("posisi", document.getElementById("job-title").value);
  formData.append("deskripsi", quill.getSemanticHTML());
  formData.append("jenis_pekerjaan", document.getElementById("job-type").value);
  formData.append(
    "jenis_lokasi",
    document.getElementById("location-type").value
  );

  // Append the files
  files.forEach((file, index) => {
    formData.append(`images[${index}]`, file);
  });

  const xhr = new XMLHttpRequest();

  const jobId = window.location.pathname.split("/")[3];
  xhr.open("POST", "/lowongan/update/" + jobId, true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      var response = JSON.parse(xhr.responseText);
      if (xhr.status === 200) {
        redirectToRootWithToast("success", "Job is updated!");
      } else {
        toast("error", "Failed: maybe some of the fields are too long?");
      }
    }
  };

  xhr.send(formData); // Send the FormData object
}

function updateImagePreviews() {
  imagePreviewContainer.innerHTML = "";
  if (files.length > 0) {
    uploadInstructions.style.display = "none";
  } else {
    uploadInstructions.style.display = "block";
  }
  files.forEach((file, index) => {
    const reader = new FileReader();
    reader.onload = function (e) {
      const preview = document.createElement("div");
      preview.className = "image-preview";
      preview.innerHTML = `
                <img src="${e.target.result}" alt="Image preview">
                <button class="remove-image" data-index="${index}">&times;</button>
            `;
      imagePreviewContainer.appendChild(preview);
    };
    reader.readAsDataURL(file);
  });
}

imagePreviewContainer.addEventListener("click", (e) => {
  if (e.target.classList.contains("remove-image")) {
    const index = parseInt(e.target.getAttribute("data-index"));
    files.splice(index, 1);
    updateImagePreviews();
  }
});

document
  .getElementsByClassName("cancel-btn")[0]
  .addEventListener("click", function () {
    var path = window.location.pathname;
    var parts = path.split("/");
    window.location.href = "/job/" + parts[parts.length - 2];
  });

document
  .getElementById("job-post-form")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    // First, fetch the company_id
    const xhrSelf = new XMLHttpRequest();
    xhrSelf.open("GET", "/api/self", true);

    xhrSelf.onload = function () {
      if (xhrSelf.status >= 200 && xhrSelf.status < 300) {
        const response = JSON.parse(xhrSelf.responseText);
        if (response.status === "success" && response.data.role === "company") {
          submitJobPosting(response.data.user_id);
        } else {
          toast(
            "error",
            "Unable to fetch company information or user is not a company."
          );
        }
      } else {
        toast("error", "An error occurred while fetching company information.");
      }
    };

    xhrSelf.onerror = function () {
      toast("error", "An error occurred while fetching company information.");
    };

    xhrSelf.send();
  });
