<div class="application-container">
    <div class="job-header">
        <h1 class="job-title">Backend Engineer</h1>
        <p class="company-name">Paper.id · Jakarta, Indonesia</p>
    </div>
    <form id="application-form">
        <div class="upload-section">
            <input type="file" id="cv-upload" accept=".pdf" style="display: none;">
            <button type="button" class="upload-button" onclick="document.getElementById('cv-upload').click()">
                <i data-lucide="file-text" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                Upload CV
            </button>
            <div class="file-info">PDF only (max 2 MB)</div>
            <div id="cv-file-name" class="file-name"></div>
            <div id="cv-error" class="error-message"></div>
        </div>
        <div class="upload-section">
            <input type="file" id="video-upload" accept="video/*" style="display: none;">
            <button type="button" class="upload-button" onclick="document.getElementById('video-upload').click()">
                <i data-lucide="video" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                Upload Introduction Video
            </button>
            <div class="file-info">Any video format (max 100 MB)</div>
            <div id="video-file-name" class="file-name"></div>
            <div id="video-error" class="error-message"></div>
        </div>
        <button type="submit" class="submit-button">Submit Application</button>
    </form>
    <div class="disclaimer">
        Submitting this application won't change your LinkinPurry profile.<br>
        Application powered by <a href="/" class="help-link">LinkinPurry</a>
    </div>
</div>