<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile</title>
    <link rel="stylesheet" href="/public/css/style1.css">
</head>
<body>

    <header>
        <h1>Company Profile</h1>
    </header>

    <main>
        <section class="profile-container">
            <h2>Profile Details</h2>
            
            <!-- Display Company Profile -->
            <div class="profile-info">
                <p><strong>Location:</strong> <?= htmlspecialchars($companyDetails->lokasi) ?></p>
                <p><strong>About:</strong> <?= htmlspecialchars($companyDetails->about) ?></p>
            </div>

            <!-- Edit Profile Form -->
            <h3>Update Profile</h3>
            <form action="/company/<?= $companyDetails->company_id ?>/profile" method="POST">
                <div>
                    <label for="lokasi">Location:</label>
                    <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($companyDetails->lokasi) ?>" required>
                </div>

                <div>
                    <label for="about">About:</label>
                    <textarea id="about" name="about" required><?= htmlspecialchars($companyDetails->about) ?></textarea>
                </div>

                <div>
                    <button type="submit">Update Profile</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 LinkInPurry. All rights reserved.</p>
    </footer>

    <!-- <script src="/public/js/script.js"></script> -->
</body>
</html>
