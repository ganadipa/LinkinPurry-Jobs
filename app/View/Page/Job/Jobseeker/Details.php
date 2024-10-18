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
        <h1 class="job-title" id="jobTitle">
            <?= $job['title'] ?>
        </h1>
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
            <span class='tag'>Open</span>
        </div>
        <div class="image-carousel">
            <img src="" alt="Job Image" class="carousel-image" id="carouselImage">
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
    </div>
    <div class="sticky-apply">
        <div class="job-details">
            <i data-lucide="users" class='mr-icon-md'></i>
            <span>
                <?= $numberOfApplicantsMessage ?>
            </span>
        </div>
        <div>
        <a href="/job/<?php echo $job['id']; ?>/apply" class="button button-primary" id="applyButton">
            <i data-lucide="send" class='lucide-sm mr-icon-sm'></i>
            Apply
        </a>
            <!-- <button class="button button-secondary">
                <i data-lucide="bookmark"></i>
                Save
            </button> -->
        </div>
    </div>
</div>