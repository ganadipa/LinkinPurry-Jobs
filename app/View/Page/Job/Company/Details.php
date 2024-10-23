<?php
use App\View\View;
?>

<div class="container">
    <div class="header">
        <?= View::render('Partial', 'CompanyCard', [
            'company' => [
                'name' => $company['name'],
                'location' => $company['location']
            ]   
        ]) ?>
        <div class="job-title-container">
            <h1 class="job-title" id="jobTitle">
                <?= $job['title'] ?>
            </h1>
            <?php 
                echo '<a href="/company/job/' . $job['id'] . '/edit" class="button edit-button" id="editJobButton">
                    <i data-lucide="file-edit" class="lucide-sm mr-icon-sm"></i>
                    Edit
                </a>';
            ?>
        </div>
        <div class="job-details">
            <i data-lucide="briefcase" class='lucide-md mr-icon-sm'></i>
            <span id="jobType">
                <?= $job['type'] ?>
            </span>
        </div>
        <div class="job-details">
            <i data-lucide="map-pin" class='lucide-md mr-icon-sm'></i>
            <span id="jobLocation">
                <?= $job['location'] ?>
            </span>
        </div>
        <div class="job-details">
            <i data-lucide="calendar" class='lucide-md mr-icon-sm'></i>
            <span id="jobCreated">
                <?= $job['created'] ?>
            </span>
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
                <?= $numberOfApplicantsMessage ?>
            </span>
        </div>
        <div>
            <button class="button button-primary" id="statusButton">
                <?= $job['isOpen'] ? 'Close Vacancy' : 'Open Vacancy' ?>
            </button>
            <button class="button button-danger" id="deleteButton">
                Delete Vacancy
            </button>
            <!-- <button class="button button-secondary">
                <i data-lucide="bookmark"></i>
                Save
            </button> -->
        </div>
    </div>
</div>