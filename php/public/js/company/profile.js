lucide.createIcons();

document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editModal');
    const editBtn = document.getElementById('editProfileBtn');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelEdit');
    const profileForm = document.getElementById('profile-form');
  
    function openModal() {
      editModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  
    function closeModal() {
      editModal.style.display = 'none';
      document.body.style.overflow = '';
    }
  
    editBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
  
    // Close modal when clicking outside
    editModal.addEventListener('click', function(e) {
      if (e.target === editModal) {
        closeModal();
      }
    });
  
    // Handle form submission
    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
      
        const formData = new FormData(this);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/profile/update', true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        closeModal();
                        window.location.reload();
                    } else {
                        alert('Failed to update profile: ' + response.message);
                    }
                } else {
                    alert('An error occurred while updating the profile.');
                }
            }
        };

        xhr.send(formData);
    });
  });