<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <script>
        // Function to handle Add/Update Profile
        function saveProfile() {
            const userId = document.getElementById('user_id').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;

            const data = new FormData();
            data.append('email', email);
            data.append('password', password);
            data.append('role', role);

            fetch(`/home/add/${userId}`, {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile saved successfully');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Function to handle Remove Profile
        function removeProfile() {
            const userId = document.getElementById('remove_user_id').value;

            fetch(`/home/remove/${userId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile removed successfully');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <h1>User Profile Management</h1>

    <!-- Add/Update Profile Form -->
    <form id="addUpdateForm" onsubmit="event.preventDefault(); saveProfile();">
        <h2>Add or Update Profile</h2>
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="JOBSEEKER">Jobseeker</option>
            <option value="COMPANY">Company</option>
        </select><br>

        <button type="submit">Save Profile</button>
    </form>

    <!-- Remove Profile Form -->
    <form id="removeForm" onsubmit="event.preventDefault(); removeProfile();">
        <h2>Remove Profile</h2>
        <label for="remove_user_id">User ID:</label>
        <input type="text" id="remove_user_id" name="user_id" required><br>

        <button type="submit">Remove Profile</button>
    </form>
</body>
</html>
