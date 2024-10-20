<aside class="left-sidebar">
    <div class="menu-card" id="my-items">
        <div class="menu-title">
            <img src="/public/images/bookmark.png" alt="My Jobs">
            <h2>My items</h2>
        </div>
        <ul>
            <li class="active">Posted jobs<span class="count">1</span></li>
        </ul>
    </div>
    <div id="search" class="menu-card">
        <input type="text" id="search-input" placeholder="Search jobs...">
    </div>
    <div class="menu-card" id="search-filter">
        <div class="menu-title">
            <h2>Filter</h2>
        </div>
        <div class="search-filter">
            <div class="filter-group">
                <h3>Job Type</h3>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="job-type" value="" checked>
                        <span class="radio-custom"></span>
                        All Job Types
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="job-type" value="full-time">
                        <span class="radio-custom"></span>
                        Full Time
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="job-type" value="part-time">
                        <span class="radio-custom"></span>
                        Part Time
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="job-type" value="internship">
                        <span class="radio-custom"></span>
                        Internship
                    </label>
                </div>
            </div>
            <div class="filter-group">
                <h3>Location Type</h3>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="location-type" value="" checked>
                        <span class="radio-custom"></span>
                        All Location Types
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="location-type" value="on-site">
                        <span class="radio-custom"></span>
                        On-site
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="location-type" value="hybrid">
                        <span class="radio-custom"></span>
                        Hybrid
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="location-type" value="remote">
                        <span class="radio-custom"></span>
                        Remote
                    </label>
                </div>
            </div>
            <div class="filter-group">
                <h3>Sort Order</h3>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="sort-order" value="desc" checked>
                        <span class="radio-custom"></span>
                        Newest First
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="sort-order" value="asc">
                        <span class="radio-custom"></span>
                        Oldest First
                    </label>
                </div>
            </div>
        </div>
    </div>
</aside>
<section class="main-content">
    <h1>Posted Jobs</h1>
    <div class="loading-animation" id="loading-animation"></div>
    <div id="job-list"></div>
    <div class="loading-animation" id="loading" style="display: none;"></div>
</section>