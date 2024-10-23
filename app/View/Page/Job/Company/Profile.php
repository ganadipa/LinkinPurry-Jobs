<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/style.css">
    <title>Company Profile - <?= htmlspecialchars($user->nama) ?></title>
</head>
<body>
    <div class="profile-container">
        <!-- Company Info Card -->
        <section class="profile-card">
            <img src="https://placehold.co/1200x300" alt="Background" class="background-image">
            <img src="https://placehold.co/150x150" alt="Profile Picture" class="profile-pic large">
            <h1><?= htmlspecialchars($user->nama) ?></h1>
            <p><?= htmlspecialchars($user->email) ?></p>
            <p><?= htmlspecialchars($companyDetail->lokasi) ?></p>
        </section>

        <!-- About Section -->
        <section class="profile-card">
            <h2>About</h2>
            <p><?= nl2br(htmlspecialchars($companyDetail->about)) ?></p>
        </section>

        <!-- Edit Form for Profile -->
        <div class="profile-edit">
            <h2>Edit Company Profile</h2>
            <form id="profile-form">
                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->nama) ?>" required>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                </div>

                <!-- Location Field -->
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" value="<?= htmlspecialchars($companyDetail->lokasi) ?>" required>
                </div>

                <!-- About Field -->
                <div class="form-group">
                    <label for="about">About</label>
                    <textarea id="about" name="about" rows="5" required><?= htmlspecialchars($companyDetail->about) ?></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
