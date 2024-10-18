// Initialize Quill editor
const quill = new Quill('#editor', {
    theme: 'snow'
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