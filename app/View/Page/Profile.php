<div class="profile-container">
    <h1>Company Profile</h1>

    <form id="profile-form">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= $companyDetail['name'] ?>" required>
        </div>

        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?= $companyDetail['location'] ?>" required>
        </div>

        <div class="form-group">
            <label for="about">About:</label>
            <textarea id="about" name="about" rows="5" required><?= $companyDetail['about'] ?></textarea>
        </div>

        <button type="submit">Save Changes</button>
    </form>
</div>
