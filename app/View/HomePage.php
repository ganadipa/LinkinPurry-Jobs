<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkinPurry - Professional Network for Purry</title>
    <link rel="stylesheet" href="\public\css\style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">LinkinPurry</div>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#network">My Network</a></li>
                <li><a href="#jobs">Jobs</a></li>
                <li><a href="#messaging">Messaging</a></li>
                <li><a href="#notifications">Notifications</a></li>
            </ul>
            <div class="profile">
                <img src="https://placehold.co/40x40" alt="Profile Picture" class="profile-pic">
                <span>Dabbir</span>
            </div>
        </nav>
    </header>

    <main>
        <section class="profile-card">
            <img src="https://placehold.co/1200x300" alt="Background" class="background-image">
            <img src="https://placehold.co/150x150" alt="Profile Picture" class="profile-pic large">
            <h1>Ahmad Mudabbir Arif</h1>
            <p>Professional IT Consultant | Full Stack Developer | Tech Enthusiast</p>
        </section>

        <section class="feed">
            <h2>Recent Posts</h2>
            <div class="post">
                <img src="https://placehold.co/50x50" alt="User" class="user-pic">
                <div class="post-content">
                    <h3>First Post</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Similique corporis iure earum.</p>
                </div>
            </div>
            <div class="post">
                <img src="https://placehold.co/50x50" alt="User" class="user-pic">
                <div class="post-content">
                    <h3>Second Post</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis eum at quam.</p>
                </div>
            </div>
        </section>

        <section class="job-section">
            <h2>Job Listings</h2>
            <button class="add-job">+ Add New Job</button>
            
            <div class="search-sort-filter">
                <input type="text" placeholder="Search jobs...">
                <select id="sort-select">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                </select>
                <select id="filter-select">
                    <option value="all">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            
            <div class="job-list">
                <!-- Job items will be dynamically added here -->
            </div>
            
            <div class="pagination">
                <!-- Pagination buttons will be dynamically added here -->
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 LinkinPurry. All rights reserved.</p>
    </footer>

    <script src="\public\js\script.js"></script>
</body>
</html>