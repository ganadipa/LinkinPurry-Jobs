document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        user_id: formData.get('user_id'),
        lokasi: formData.get('location'),
        about: formData.get('about')
    };

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/profile/update', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

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

    xhr.send(JSON.stringify(data));
});
