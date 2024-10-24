<header>
    <nav>
        <div class="nav-left">
            <a href="/" aria-label="LinkedIn Home"><img src="/public/images/linkedin.png" alt="LinkedIn" class="logo"></a>
            <form class="search-bar" method="GET" action="/">
                <img src="/public/images/search.png" alt="Search Icon" class="search-icon">
                <input type="text" placeholder="Search" class="search-input" name="q">
            </form>
        </div>
        <div class="nav-right">
            <a href="/" class="nav-link"><img src="/public/images/home.png" ><span>Home</span></a>
            <?php
                if ($user == null) {
                    echo '<a href="/login" class="nav-link" id="login"><img src="/public/images/log-in.png" ><span>Login</span></a>';
                    echo '<a href="/register" class="nav-link" id="register"><img src="/public/images/add.png" ><span>Register</span></a>';
                } else {
                    if ($user->role->value === 'jobseeker') {
                        echo '<a href="/jobseeker/history" class="nav-link" id="history"><img src="/public/images/history.png" ><span>History</span></a>';
                    } else if ($user->role->value === 'company') {
                        echo '<a href="/profile" class="nav-link" id="profile"><img src="/public/images/account.png" ><span>Profile</span></a>';
                    }
                    echo '<div class="nav-link" id="logout"><img src="/public/images/logout.png" ><span>Logout</span></div>';
                }
            ?>
        </div>
        <!-- Hamburger Menu Icon -->
        <div class="hamburger" id="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </nav>
</header>

<!-- Sidenav -->
<div id="sidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" id="closebtn">&times;</a>
    <a href="/">Home</a>
    <?php
        if ($user == null) {
            echo '<a href="/login">Login</a>';
            echo '<a href="/register">Register</a>';
        } else {
            if ($user->role->value === 'jobseeker') {
                echo '<a href="/jobseeker/history">History</a>';
            } else if ($user->role->value === 'company') {
                echo '<a href="/profile">Profile</a>';
            }
            echo '<a id="logout-sm">Logout</a> ';
        }
    ?>
</div>

<!-- Optional Overlay -->
<div id="overlay" class="overlay"></div>