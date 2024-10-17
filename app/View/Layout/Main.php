<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            echo $title
        ?>
    </title>
    <link rel="icon" href="/public/favicon.ico" type="image/x-icon">
    
    <link rel="stylesheet" href="/public/css/global.css">
    <link rel="stylesheet" href="/public/css/utils.css">
    <?php
        if (isset($css)) {
            foreach ($css as $style) {
                echo "<link rel='stylesheet' href='/public/css/$style'>";
            }
        }

        if (isset($js)) {
            foreach ($js as $script) {
                echo "<script src='/public/js/$script' defer></script>";
            }
        }
    ?>
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
    <?= $content ?>
    </main>
</body>
</html>