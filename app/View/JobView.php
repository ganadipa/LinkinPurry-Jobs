<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkedIn - Posted Jobs</title>
    <link rel="stylesheet" href="public\css\job\style.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                <img src="public\images\linkedin.png" alt="LinkedIn" class="logo">
                <div class="search-bar">
                    <img src="public\images\search.png" alt="Search Icon" class="search-icon">
                    <input type="text" placeholder="Search" class="search-input">
                </div>
            </div>
            <div class="nav-right">
                <a href="#" class="nav-link"><img src="public\images\home.png" alt="Home"><span>Home</span></a>
                <a href="#" class="nav-link"><img src="public\images\people.png" alt="My Network"><span>My Network</span></a>
                <a href="#" class="nav-link"><img src="public\images\job.png" alt="Jobs"><span>Jobs</span></a>
                <a href="#" class="nav-link"><img src="public\images\messaging.png" alt="Messaging"><span>Messaging</span></a>
                <a href="#" class="nav-link"><img src="public\images\bell.png" alt="Notifications"><span>Notifications</span></a>
                <a href="#" class="nav-link"><img src="https://placehold.co/20x20" alt="Me"><span>Me</span></a>
            </div>
        </nav>
    </header>
    <main>
        <aside class="left-sidebar">
            <div class="menu-card">
                <div class="menu-title">
                    <img src="public\images\bookmark.png" alt="My Jobs">
                    <h2>My items</h2>
                </div>
                <ul>
                    <li class="active">Posted jobs<span class="count">1</span></li>
                </ul>
            </div>
        </aside>
        <section class="main-content">
            <h1>Posted Jobs</h1>
            <div class="job-card">
                <div class="job-info">
                    <img src="https://placehold.co/50x50" alt="Company Logo" class="company-logo">
                    <div>
                        <h3>Frontend Developer</h3>
                        <p>ITB Fair 2024</p>
                        <p>Makassar, South Sulawesi, Indonesia (On-site)</p>
                        <p class="draft-info">Draft • Created 3m ago</p>
                        <a href="#" class="complete-draft">Complete draft</a>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="more-actions">•••</button>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">Manage job</a>
                        <a href="#" class="dropdown-item">Delete draft</a>
                    </div>
                </div>
            </div>
        </section>
        <aside class="right-sidebar">
            <div class="action-card">
                <button class="primary-btn">Post a free job</button>
            </div>
        </aside>
    </main>
</body>
</html>