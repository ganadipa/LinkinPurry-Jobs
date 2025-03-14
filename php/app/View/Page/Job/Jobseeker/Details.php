<?php
use App\View\View;
?>

<div class="container">
    <div class="header">
        <?= View::render('Partial', 'CompanyCard', [
            'company' => [
                'name' => htmlspecialchars($company['name']),
                'location' => htmlspecialchars($company['location'])
            ]   
        ]) ?>
        <h1 class="job-title" id="jobTitle">
            <?= htmlspecialchars($job['title']) ?>
        </h1>
        <div class="job-details">
            <i data-lucide="briefcase" class='lucide-md mr-icon-sm'></i>
            <span id="jobType">
                <?= htmlspecialchars($job['type']) ?>
            </span>
        </div>
        <div class="job-details">
            <i data-lucide="map-pin" class='lucide-md mr-icon-sm'></i>
            <span id="jobLocation">
                <?= htmlspecialchars($job['location']) ?>
            </span>
        </div>
        <div class="job-details flex-col">
            <!-- Job post is created, make it 'created at'  without icon and use italic-->
            <p class="self-start">
                <span class="font-italic">Created on</span>
                <span class="server-timestamp" data-timestamp=<?= $job['created']?>>
                <?= $job['created'] ?>
            </p>
            <?php
                if ($job['created'] !== $job['updated']) {
                    echo '<p class="self-start">
                        <span class="font-italic">Updated on</span>
                        <span class="server-timestamp" data-timestamp=' . $job['updated'] . '>
                        ' . $job['updated'] . '
                    </p>';
                }
             ?>
            
        </div>
    </div>
    <div class="content">
        <div id="jobStatus">
            <span class=' red-tag blue-tag font-semibold'>
                <?= $job['isOpen'] ? 'Open' : 'Closed' ?>
            </span>
            <?php
                if ($status === 'waiting') {
                    echo '<div id="waiting" class="status-indicator">
                    <i data-lucide="clock" class="lucide-sm mr-icon-sm"></i>
                    <span>Waiting</span>
                </div>';
                } else if ($status === 'accepted') {
                    echo '<div id="accepted" class="status-indicator">
                    <i data-lucide="check-circle" class="lucide-sm mr-icon-sm"></i>
                    <span>Accepted</span>
                    </div>';
                }  else if ($status === 'rejected') {
                    echo '<div id="rejected" class="status-indicator">
                    <i data-lucide="x-circle" class="lucide-sm mr-icon-sm"></i>
                    <span>Rejected</span>
                    </div>';
                }
            ?>
        </div>

        <div class="image-carousel">
            <?php
            if ($job['images']) {
                $sz = sizeof($job['images']);
                for ($i = 0; $i < $sz; $i++) {
                    echo '<img src="/attachmentlowongan/' . $job['images'][$i]['attachment_id'] . '" alt="Job Image" class="hidden carousel-image"  >';
                }
            }
            ?>
            <button class="carousel-button prev" id="prevButton">
                <i data-lucide="chevron-left"></i>
            </button>
            <button class="carousel-button next" id="nextButton">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
        <h2 class="section-title">Job Description</h2>
        <p id="jobDescription">
            <?= $job['description'] ?>
        </p>
        <h2 class="section-title">Reason</h2>
        <p id="jobDescription">
            <?= $reason ?>
        </p>
        
    </div>
    <div class="sticky-apply">
        <div class="job-details">
            <i data-lucide="users" class='mr-icon-md'></i>
            <span>
                <?= $numberOfApplicantsMessage ?>
            </span>
        </div>
        <div>
        <?php
            if ($job['isOpen'] && !$applied) {
                echo '
                <a href="/job/' . htmlspecialchars($job['id']) . '/apply" class="button button-primary" id="applyButton">
                    <i data-lucide="send" class="lucide-sm mr-icon-sm"></i>
                    Apply
                </a>';
            }

            if ($applied) {
                if ($submission['cv']) {
                    echo '
                    <a href = "'.htmlspecialchars($submission['cv']).'" class="button button-secondary">
                        <i data-lucide="check" class="lucide-sm mr-icon-sm"></i>
                        CV
                    </a>';
                }

                if ($submission['video']) {
                    echo '
                    <a href = "'.htmlspecialchars($submission['video']).'" class="button button-secondary">
                        <i data-lucide="check" class="lucide-sm mr-icon-sm"></i>
                        Video
                    </a>';
                }
                
            }
            ?>
        </div>
    </div>
</div>