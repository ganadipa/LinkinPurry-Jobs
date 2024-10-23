<div class="profile-container">
    <h1>Company Profile</h1>

    <form id="profile-form">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->nama) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>

        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($companyDetail->lokasi) ?>" required>
        </div>

        <div class="form-group">
            <label for="about">About:</label>
            <textarea id="about" name="about" rows="5" required><?= htmlspecialchars($companyDetail->about) ?></textarea>
        </div>

        <button type="submit">Save Changes</button>
    </form>
</div>
