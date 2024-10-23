<div class="container application-details">
    <div class="header">
        <h1 class="page-title">Application Details</h1>

        <!-- Application Status moved to top-right -->
        <div class="application-status">
            <div class="status-indicator status-<?= $application['status'] ?>" id="statusIndicator">
                <i data-lucide="<?= $application['status'] === 'waiting' ? 'clock' : ($application['status'] === 'accepted' ? 'check-circle' : 'x-circle') ?>"></i>
                <span><?= ucfirst($application['status']) ?></span>
            </div>
        </div>
    </div>

    <div class="applicant-info">
        <h2>Applicant Information</h2>
        <p><strong>Name:</strong> <?= $applicant['name'] ?></p>
        <p><strong>Email:</strong> <?= $applicant['email'] ?></p>
    </div>
    
    <div class="application-attachments">
        <h2>Application Attachments</h2>
        
        <div class="attachment cv-attachment">
            <h3>CV</h3>
            <?php
                if (empty($application['cv_url'])) {
                    echo '<p>No CV uploaded</p>';
                } else {
                    echo '<embed src="/job/'. $jobId . '/apply/' . $application['id'] . '/cv" type="application/pdf" width="100%" height="600px" />';
                }
            ?>
        </div>
        
        <div class="attachment video-attachment">
            <h3>Introductory Video</h3>
            <?php
                if (empty($application['video_url'])) {
                    echo '<p>No video uploaded</p>';
                } else {
                    echo '<video width="100%" controls>
                            <source src="/job/'. $jobId . '/apply/' . $application['id'] . '/video" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>';
                }
            ?>
        </div>
    </div>
    
    
    <?php if ($application['status'] === 'waiting'): ?>
    <div class="application-action">
        <h2>Update Application Status</h2>
        <form id="updateStatusForm" action="/company/application/<?= $application['id'] ?>/update" method="POST">
            <div class="form-group">
                <label for="reason">Reason / Follow-up:</label>
                <div id="reasonEditor"></div>
                <input type="hidden" id="reasonHidden" name="reason">
            </div>
            <div class="form-group approval">
                <button type="button" class="button button-accept" id="acceptButton">Accept</button>
                <button type="button" class="button button-reject" id="rejectButton">Reject</button>
                <input type="hidden" id="statusHidden" name="status">
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>