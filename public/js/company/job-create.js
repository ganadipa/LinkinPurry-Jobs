import { redirectToRootWithToast } from "../globals.js";
import { toast } from "../toast.js";

// Initialize Quill editor
const quill = new Quill("#editor", {
  theme: "snow",
});

document.addEventListener("DOMContentLoaded", function () {
  lucide.createIcons();

  // Client side validation for job title
  const jobTitle = document.getElementById("job-title");
  jobTitle.addEventListener("focusout", function () {
    const title = this.value;
    if (title.length === 0) {
      toast("error", "Job title cannot be empty");
    }
  });
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

const cancelButton = document.getElementsByClassName("cancel-btn")[0];
cancelButton.addEventListener("click", function () {
  window.location.href = "/";
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
          // Now that we have the company_id, proceed with job posting
          submitJobPosting(response.data.user_id);
        } else {
          alert(
            "Error: Unable to fetch company information or user is not a company."
          );
        }
      } else {
        console.error("Request failed: " + xhrSelf.statusText);
        alert("An error occurred while fetching company information.");
      }
    };

    xhrSelf.onerror = function () {
      console.error("Request failed");
      alert("An error occurred while fetching company information.");
    };

    xhrSelf.send();
  });

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
  xhr.open("POST", "/lowongan/create", true);

  // No need to set 'Content-Type' as it will be automatically set to 'multipart/form-data' when using FormData
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        console.log("Success:", response);

        // Go to root (/) with:
        // 1. toast_onload_type = 'success'
        // 2. toast_onload_message = "
        //

        redirectToRootWithToast("success", "Job is posted!");
      } else {
        console.log("Error:", xhr.status, xhr.responseText);
      }
    }
  };

  xhr.send(formData); // Send the FormData object
}
