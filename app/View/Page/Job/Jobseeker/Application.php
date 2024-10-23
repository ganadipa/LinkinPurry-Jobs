


<div class="application-container">
    <div class="job-header">
        <h1 class="job-title">
            Apply for <?= $job['title'] ?>
        </h1>
        <p class="company-name">
            at <?= $job['company']. ' · ' .$job['location']?>
        </p>
    </div>


    <form id="application-form" action="/job/<?php echo $job['id']; ?>/apply" method="POST" enctype="multipart/form-data">
        <!-- CV Upload Section -->
        <div class="upload-section">
            <input type="file" name="cv" id="cv-upload" accept=".pdf" style="display: none;">
            <button type="button" class="upload-button" onclick="document.getElementById('cv-upload').click()">
                <i data-lucide="file-text" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                Upload CV
            </button>
            <div class="file-info">PDF only (max 2 MB)</div>
            <div id="cv-file-name" class="file-name"></div>
            <div id="cv-error" class="error-message"></div>
        </div>

        <!-- Video Upload Section -->
        <div class="upload-section">
            <input type="file" name="video" id="video-upload" accept="video/*" style="display: none;">
            <button type="button" class="upload-button" onclick="document.getElementById('video-upload').click()">
                <i data-lucide="video" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                Upload Video
            </button>
            <div class="file-info">Video files (max 250 MB)</div>
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