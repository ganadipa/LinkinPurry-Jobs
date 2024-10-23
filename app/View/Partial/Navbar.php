<header>
    <nav>
        <div class="nav-left">
            <a href="/" alt=""><img src="/public/images/linkedin.png" alt="LinkedIn" class="logo"></a>
            <form class="search-bar" method="GET" action="/">
                <img src="/public/images/search.png" alt="Search Icon" class="search-icon">
                <input type="text" placeholder="Search" class="search-input" name='q'>
            </form>
        </div>
        <div class="nav-right">
            <a href="/" class="nav-link"><img src="/public/images/home.png" alt="Home"><span>Home</span></a>
            <?php
                
                if ($user == null) {
                    echo '<a href="/login" class="nav-link" id="login"><img src="/public/images/log-in.png" alt="Login"></img><span>Login</span></a>';


                    echo '<a href="/register" class="nav-link" id="register"><img src="/public/images/add.png" alt="Register"></img><span>Register</span></a>
                    ';
                } else {


                    if ($user->role->value === 'jobseeker') {
                        echo '<a href="/jobseeker/history" class="nav-link" id="history"><img src="/public/images/history.png" alt="History"></img><span>History</span></a>';
                    } else if ($user->role->value === 'company') {
                        echo '<a href="/profile" class="nav-link" id="profile"><img src="/public/images/account.png" alt="Profile"></img><span>Profile</span></a>';
                    }

                    echo '<div class="nav-link" id="logout"><img src="/public/images/logout.png" alt="Logout"></img><span>Logout</span></div>
                    ';
                }


            ?>  
        </div>
    </nav>
</header>