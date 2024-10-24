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
        <div class="job-title-container">
            <h1 class="job-title" id="jobTitle">
                <?= htmlspecialchars($job['title']) ?>
            </h1>
            <?php 
                echo '<a href="/company/job/' . htmlspecialchars($job['id']) . '/edit" class="button edit-button" id="editJobButton">
                    <i data-lucide="file-edit" class="lucide-sm mr-icon-sm"></i>
                    Edit
                </a>';
            ?>
        </div>
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
                <span class="server-time" data-timestamp=<?= $job['created']?>>
                <?= $job['created'] ?>
            </p>
            <?php
                if ($job['created'] !== $job['updated']) {
                    echo '<p class="self-start">
                        <span class="font-italic">Updated on</span>
                        <span class="server-time" data-timestamp=' . $job['updated'] . '>
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

        <div id="applicantList">
            <h2 class="section-title">Applicants</h2>
            <?php 
            if (empty($applicants)) {
                echo '<p>No applicants yet</p>';
            } else {
                foreach ($applicants as $applicant) {
                    echo '<div class="applicant-card">
                            <div class="applicant-info">
                                <h3 class="applicant-name">
                                    ' . $applicant['name'] . '
                                </h3>
                                <div class="status-indicator-company status-' . $applicant['status'] . '">
                                    <i data-lucide="' . ($applicant['status'] === 'waiting' ? 'clock' : ($applicant['status'] === 'accepted' ? 'check-circle' : 'x-circle')) . '"></i>
                                    <span>' . ucfirst($applicant['status']) . '</span>
                                </div>
                            </div>
                            <div class="applicant-action">
                                <a href="/company/job/' . $job['id'] . '/application/' . $applicant['id'] . '" class="button button-secondary">
                                    View Application Details
                                </a>
                            </div>
                        </div>';
                }
            }
            ?>
        </div>
    </div>
    <div class="sticky-apply">
        <div class="job-details">
            <i data-lucide="users" class='mr-icon-md'></i>
            <span>
                <?= htmlspecialchars($numberOfApplicantsMessage) ?>
            </span>
        </div>
        <div class="wrap">
            <button class="button button-primary" id="statusButton">
                <?= $job['isOpen'] ? 'Close Vacancy' : 'Open Vacancy' ?>
            </button>
            <button class="button button-danger" id="deleteButton">
                Delete Vacancy
            </button>
        </div>
    </div>

    <!-- Add this HTML just before the closing </div> of the container -->
    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Deletion</h2>
                <button class="close-button" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this job vacancy? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="button button-secondary" id="cancelDelete">Cancel</button>
                <button class="button button-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>