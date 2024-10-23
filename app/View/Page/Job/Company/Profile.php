<div class="main-container">
  <div class="profile-container">
    <div class="profile-background">
      <img src="/public/images/banner-placeholder.svg" alt="Background" class="background-image">
      <button class="edit-button" id="editProfileBtn">
        <i data-lucide="pencil" class="mr-icon-md"></i>
      </button>
    </div>
    
    <div class="profile-content">
      <div class="profile-pic-container">
        <img src="/public/images/img-placeholder.svg" alt="Profile Picture" class="profile-pic">
      </div>
      
      <div class="profile-info">
        <h1><?= htmlspecialchars($user->nama) ?></h1>
        <p><?= htmlspecialchars($user->email) ?></p>
        <p><?= htmlspecialchars($companyDetail->lokasi) ?></p>
      </div>
    </div>
    
    <div class="profile-section">
      <h2>About</h2>
      <p><?= nl2br(htmlspecialchars($companyDetail->about)) ?></p>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <div class="modal-header">
      <h2>Edit Company Profile</h2>
      <button class="close-button" id="closeModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>
    
    <form id="profile-form">
      <div class="modal-body">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->nama) ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
        </div>

        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" value="<?= htmlspecialchars($companyDetail->lokasi) ?>" required>
        </div>

        <div class="form-group">
          <label for="about">About</label>
          <textarea id="about" name="about" rows="5" required><?= htmlspecialchars($companyDetail->about) ?></textarea>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
  </div>
</div>