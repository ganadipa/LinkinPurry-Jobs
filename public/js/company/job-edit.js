// Initialize Quill editor
const quill = new Quill('#editor', {
    theme: 'snow'
});

// Initialize lucide icons
document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons();
});

const dragDropArea = document.getElementById('drag-drop-area');
const fileInput = document.getElementById('file-input');
const imagePreviewContainer = document.getElementById('image-preview-container');
const uploadInstructions = document.getElementById('upload-instructions');
const uploadButton = document.querySelector('.upload-button');
let files = [];

uploadButton.addEventListener('click', (e) => {
    e.stopPropagation();
    fileInput.click();
});

dragDropArea.addEventListener('click', (e) => {
    if (e.target === dragDropArea || e.target === uploadInstructions || uploadInstructions.contains(e.target)) {
        fileInput.click();
    }
});

dragDropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dragDropArea.classList.add('dragover');
});

dragDropArea.addEventListener('dragleave', () => {
    dragDropArea.classList.remove('dragover');
});

dragDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dragDropArea.classList.remove('dragover');
    const droppedFiles = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
    handleFiles(droppedFiles);
});

fileInput.addEventListener('change', (e) => {
    const selectedFiles = Array.from(e.target.files);
    handleFiles(selectedFiles);
});

function handleFiles(newFiles) {
    files = [...files, ...newFiles];
    updateImagePreviews();
}

function updateImagePreviews() {
    imagePreviewContainer.innerHTML = '';
    if (files.length > 0) {
        uploadInstructions.style.display = 'none';
    } else {
        uploadInstructions.style.display = 'block';
    }
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.className = 'image-preview';
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Image preview">
                <button class="remove-image" data-index="${index}">&times;</button>
            `;
            imagePreviewContainer.appendChild(preview);
        }
        reader.readAsDataURL(file);
    });
}

imagePreviewContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-image')) {
        const index = parseInt(e.target.getAttribute('data-index'));
        files.splice(index, 1);
        updateImagePreviews();
    }
});

document.getElementById('job-post-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Here you would normally send the form data to your server
    console.log('Form submitted');
    console.log('Job Title:', document.getElementById('job-title').value);
    console.log('Company:', document.getElementById('company').value);
    console.log('Workplace Type:', document.getElementById('workplace-type').value);
    console.log('Job Location:', document.getElementById('job-location').value);
    console.log('Job Type:', document.getElementById('job-type').value);
    console.log('Description:', quill.root.innerHTML);
    console.log('Created At:', new Date().toISOString().split('T')[0]);
});