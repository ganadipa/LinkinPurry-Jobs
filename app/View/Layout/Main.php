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
    <link rel="stylesheet" href="/public/css/text.css">

    <?php 
        if(isset($ext_css)) {
            foreach ($ext_css as $style) {
                echo "<link rel='stylesheet' href='$style'>";
            }
        }

        if (isset($ext_js)) {
            foreach ($ext_js as $script) {
                echo "<script src='$script' defer type='module'></script>";
            }
        }
    ?>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/public/js/globals.js" defer type="module"></script>

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
    <?= View::render('Partial', 'Navbar', [
        'user' => $user
    ]) ?>
    <main>
    <?= $content ?>
    </main>
</body>
</html>