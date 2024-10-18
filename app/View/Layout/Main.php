<?php

namespace App\View\Layout;
use App\View\View;

?>


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
    
    <link rel="stylesheet" href="/public/css/globals.css">
    <link rel="stylesheet" href="/public/css/utils.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <?php
        if (isset($css)) {
            foreach ($css as $style) {
                echo "<link rel='stylesheet' href='/public/css/$style'>";
            }
        }

        if (isset($js)) {
            foreach ($js as $script) {
                echo "<script src='/public/js/$script' defer type='module'></script>";
            }
        }
    ?>
</head>
<body>
    <?= View::render('Partial', 'Navbar', []) ?>
    <main>
    <?= $content ?>
    </main>
</body>
</html>