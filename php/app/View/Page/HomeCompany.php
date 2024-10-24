<!-- Main Content -->
<aside class="left-sidebar">
    <div class="menu-card" id="my-items">
        <div class="menu-title">
            <img src="/public/images/bookmark.png" alt="My Jobs">
            <h2>Jobs Information</h2>
        </div>
        <ul>
            <li class="active">Posted jobs<span class="count" id='count-jobs'>
                <?= $numberOfJobs ?>
            </span></li>
        </ul>
    </div>

    <form class="menu-card sticky" id="search-filter">
        <div id="search" class="menu-card">
            <input type="text" id="search-input" placeholder="Search jobs..." name="q"
                value="<?php echo htmlspecialchars($filter['q']); ?>">
        </div>

        <div class="menu-title">
            <h2>Filter</h2>
        </div>

        <div class="search-filter">
            <!-- Job Type Filters -->
            <div class="filter-group">
                <h3>Job Type</h3>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="job-type[]" value="full-time"
                            <?php echo $filter['jobType']['full-time'] ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        Full Time
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="job-type[]" value="part-time"
                            <?php echo $filter['jobType']['part-time'] ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        Part Time
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="job-type[]" value="internship"
                            <?php echo $filter['jobType']['internship'] ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        Internship
                    </label>
                </div>
            </div>

            <!-- Location Type Filters -->
            <div class="filter-group">
                <h3>Location Type</h3>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="location-type[]" value="on-site"
                            <?php echo $filter['locationType']['on-site'] ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        On-site
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="location-type[]" value="hybrid"
                            <?php echo $filter['locationType']['hybrid'] ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        Hybrid
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="location-type[]" value="remote"
                            <?php echo $filter['locationType']['remote'] ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        Remote
                    </label>
                </div>
            </div>

            <!-- Sort Order Filters -->
            <div class="filter-group">
                <h3>Sort Order</h3>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="sort-order" value="desc"
                            <?php echo ($filter['sortOrder'] == 'desc') ? 'checked' : ''; ?>>
                        <span class="radio-custom"></span>
                        Newest First
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="sort-order" value="asc"
                            <?php echo ($filter['sortOrder'] == 'asc') ? 'checked' : ''; ?>>
                        <span class="radio-custom"></span>
                        Oldest First
                    </label>
                </div>
            </div>
        </div>

        <div class="filter-group">
            <button type="submit" id="apply-filters" class="apply-button">Apply Filters</button>
        </div>
    </form>
</aside>

<section class="main-content">
    <h1>Posted Jobs</h1>
    <div class="loading-animation" id="loading-animation" style="display: none;"></div>
    <div id="job-list"></div>
    <div class="loading-animation" id="loading" style="display: none;"></div>
</section>

<aside class="right-sidebar">
    <div class="action-card sticky">
        <a href="/company/job/create" class="primary-btn w-full">
            Post a Job
        </a>
    </div>
</aside>