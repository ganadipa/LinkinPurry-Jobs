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
    
    // First, fetch the company_id
    const xhrSelf = new XMLHttpRequest();
    xhrSelf.open('GET', '/api/self', true);
    
    xhrSelf.onload = function() {
        if (xhrSelf.status >= 200 && xhrSelf.status < 300) {
            const response = JSON.parse(xhrSelf.responseText);
            if (response.status === 'success' && response.data.role === 'company') {
                // Now that we have the company_id, proceed with job posting
                submitJobPosting(response.data.user_id);
            } else {
                alert('Error: Unable to fetch company information or user is not a company.');
            }
        } else {
            console.error('Request failed: ' + xhrSelf.statusText);
            alert('An error occurred while fetching company information.');
        }
    };
    
    xhrSelf.onerror = function() {
        console.error('Request failed');
        alert('An error occurred while fetching company information.');
    };
    
    xhrSelf.send();
});

function submitJobPosting(companyId) {
    console.log('Job Title:', document.getElementById('job-title').value);
    console.log('Description:', quill.root.innerHTML);
    console.log('Job Type:', document.getElementById('job-type').value);
    console.log('Location Type:', document.getElementById('location-type').value);
    console.log('Job Location:', document.getElementById('job-location').value);

    const formData = {
        company_id: companyId,
        posisi: document.getElementById('job-title').value,
        deskripsi: quill.root.innerHTML,
        jenis_pekerjaan: document.getElementById('job-type').value,
        jenis_lokasi: document.getElementById('location-type').value,
        location: document.getElementById('job-location').value,
        images: files.map(file => file.name)
    };

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/lowongan/update/' + companyId, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                console.log('Success:', response);
            } else {
                console.log('Error:', xhr.status, xhr.responseText);
            }
        }
    };

    console.log('Sending data:', JSON.stringify(formData));
    xhr.send(JSON.stringify(formData));
}