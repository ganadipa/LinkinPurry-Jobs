<div class="container">
    <section class="main-content">
        <h1>Create Job</h1>
        <form id="job-post-form">
            <div class="form-group">
                <label for="job-title">Job title (Position)*</label>
                <input type="text" id="job-title" name="job-title" required>
            </div>
            <div class="form-group">
                <label for="location-type">Location type*</label>
                <select id="location-type" name="location-type" required>
                    <option value="on-site">On-site</option>
                    <option value="hybrid">Hybrid</option>
                    <option value="remote">Remote</option>
                </select>
            </div>
            <div class="form-group">
                <label for="job-type">Job type*</label>
                <select id="job-type" name="job-type" required>
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="internship">Internship</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editor">Job Description*</label>
                <div id="editor">
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
                    <div id="image-preview-container" class="image-preview-container"></div>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn">Create Job</button>
            </div>
        </form>
    </section>
    <aside class="right-sidebar">
        <div class="info-card">
            <h2>Target your job to the right people</h2>
            <p>Include a job description and add required skills to target job seekers who match your criteria.</p>
        </div>
    </aside>
</div>