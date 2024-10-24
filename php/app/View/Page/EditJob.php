<section class="main-content">
    <h1>Review job description</h1>
    <form id="job-post-form">
        <div class="form-group">
            <label for="job-title">Job title*</label>
            <input type="text" id="job-title" name="job-title" required value="<?php echo htmlspecialchars($jobData['title']); ?>">
        </div>
        <!-- <div class="form-group">
            <label for="company">Company*</label>
            <input type="text" id="company" name="company" required value="<?php echo htmlspecialchars($jobData['company']); ?>">
        </div> -->
        <div class="form-group">
            <label for="location-type">Location type*</label>
            <select id="location-type" name="location-type" required>
                <option value="on-site" <?php echo $jobData['locationType'] == 'on-site' ? 'selected' : ''; ?>>On-site</option>
                <option value="hybrid" <?php echo $jobData['locationType'] == 'hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                <option value="remote" <?php echo $jobData['locationType'] == 'remote' ? 'selected' : ''; ?>>Remote</option>
            </select>
        </div>
        <div class="form-group">
            <label for="job-type">Job type*</label>
            <select id="job-type" name="job-type" required>
                <option value="full-time" <?php echo $jobData['jobType'] == 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                <option value="part-time" <?php echo $jobData['jobType'] == 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                <option value="internship" <?php echo $jobData['jobType'] == 'internship' ? 'selected' : ''; ?>>Internship</option>
            </select>
        </div>
        <div class="form-group">
            <label for="editor">Job Description*</label>
            <div id="editor">
                <?php echo $jobData['description']; ?>
            </div>
        </div>
        <div class="form-group">
            <label for="attachments">Attachment(s)</label>
            <div id="drag-drop-area" class="drag-drop-area">
                <div id="upload-instructions">
                    <i data-lucide="upload" class="lucide-md"></i>
                    <p>Drag and drop files here</p>
                    <p>or</p>
                    <button type="button" class="upload-button">Choose Files</button>
                </div>
                <input type="file" id="file-input" multiple accept="image/*" style="display: none;">
                <div id="image-preview-container" class="image-preview-container">
                    <?php foreach ($jobData['attachments'] as $attachment): ?>
                        <div class="image-preview">
                            <img src="<?php echo htmlspecialchars($attachment); ?>" alt="Attachment">
                            <button type="button" class="remove-image" data-filename="<?php echo htmlspecialchars($attachment); ?>">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="submit-btn">Save Changes</button>
            <button type="button" class="cancel-btn">Cancel</button>
        </div>
    </form>
</section>
<aside class="right-sidebar">
    <div class="info-card">
        <h2><?php echo htmlspecialchars($jobData['title']); ?></h2>
        <p><?php
            echo htmlspecialchars($jobData['company']);
        ?>
        </p>
        <p>Saved as Draft</p>
    </div>
    <div class="info-card">
        <h2>Target your job to the right people</h2>
        <p>Include a job description and add required skills to target job seekers who match your criteria.</p>
    </div>
</aside>