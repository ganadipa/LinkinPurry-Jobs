<header>
    <nav>
        <div class="nav-left">
            <img src="/public/images/linkedin.png" alt="LinkedIn" class="logo">
            <form class="search-bar" method="GET" action="/">
                <img src="/public/images/search.png" alt="Search Icon" class="search-icon">
                <input type="text" placeholder="Search" class="search-input" name='q'>
            </form>
        </div>
        <div class="nav-right">
            <a href="/" class="nav-link"><img src="/public/images/home.png" alt="Home"><span>Home</span></a>
            <?php
                
                if ($user == null) {
                    echo '<a href="/login" class="nav-link" id="login"><img src="/public/images/log-in.png"></img><span>Login</span></a>';
                } else {
                    echo '<div class="nav-link" id="logout"><img src="/public/images/logout.png"></img><span>Logout</span></div>
                    ';
                }
            ?>  
        </div>
    </nav>
</header>