<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkedIn Job Search</title>
    <link rel="stylesheet" href="public\css\company\style.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                <img src="public\images\linkedin.png" alt="LinkedIn Logo" class="logo">
                <div class="search-container">
                    <div class="search-bar">
                        <img src="public\images\search.png" alt="Search Icon" class="search-icon">
                        <input type="text" placeholder="Title, skill or company" class="search-input">
                    </div>
                    <div class="search-bar">
                        <img src="public\images\location-pin.png" alt="Location Icon" class="search-icon">
                        <input type="text" placeholder="City, state, or zip code" class="search-input">
                    </div>
                </div>
            </div>
            <div class="nav-right">
                <a href="#" class="nav-link"><img src="public\images\home.png" alt="Home"><span>Home</span></a>
                <a href="#" class="nav-link"><img src="public\images\people.png" alt="Network"><span>Network</span></a>
                <a href="#" class="nav-link"><img src="public\images\job.png" alt="Jobs"><span>Jobs</span></a>
                <a href="#" class="nav-link"><img src="public\images\messaging.png" alt="Messaging"><span>Messaging</span></a>
                <a href="#" class="nav-link"><img src="public\images\bell.png" alt="Notifications"><span>Notifications</span></a>
                <a href="#" class="nav-link"><img src="https://placehold.co/20x20" alt="Me"><span>Me</span></a>
            </div>
        </nav>
    </header>
    <main>
        <aside>
            <div class="profile-card">
                <div class="banner"></div>
                <img src="https://placehold.co/40x40" alt="Profile Picture" class="profile-picture">
                <h2>Ahmad Mudabbir</h2>
                <p>School of Electrical and Informatics Engineering</p>
                <div class="location">Institut Teknologi Bandung</div>
            </div>
            <div class="job-tools">
                <a href="#" class="job-tool-link"><img src="public\images\bookmark.png" alt="My Jobs">My Jobs</a>
                <a href="#" class="job-tool-link"><img src="public\images\list.png" alt="Preferences">Preferences</a>
                <a href="#" class="job-tool-link"><img src="public\images\document.png" alt="Interview prep">Interview prep</a>
                <a href="#" class="job-tool-link"><img src="public\images\youtube.png" alt="Job seeker guidance">Job seeker guidance</a>
            </div>
        </aside>
        <section class="job-content">
            <div class="suggested-searches">
                <h2>Suggested job searches</h2>
                <div class="search-tags">
                    <a href="#" class="tag">remote</a>
                    <a href="#" class="tag">marketing manager</a>
                    <a href="#" class="tag">hr</a>
                    <a href="#" class="tag">legal</a>
                    <a href="#" class="tag">sales</a>
                </div>
            </div>
            <div class="job-listings">
                <h2>Top job picks for you</h2>
                <div class="job-card">
                    <h3>Software Developer</h3>
                    <p class="company">TechCorp</p>
                    <p class="location">New York, NY (Remote)</p>
                    <button class="easy-apply">Easy Apply</button>
                </div>
                <div class="job-card">
                    <h3>Data Analyst</h3>
                    <p class="company">DataCo</p>
                    <p class="location">San Francisco, CA (On-site)</p>
                    <button class="easy-apply">Easy Apply</button>
                </div>
                <a href="#" class="see-all">Show all →</a>
            </div>
        </section>
    </main>
</body>
</html>