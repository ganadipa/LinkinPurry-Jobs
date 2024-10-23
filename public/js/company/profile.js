document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/profile/update', true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status >= 200 && xhr.status < 300) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert('Profile updated successfully!');
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
