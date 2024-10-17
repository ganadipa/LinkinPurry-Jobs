<?php
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
    <link rel="stylesheet" href="/public/css/toast.css">

    <script src="https://unpkg.com/lucide@latest"></script>

    <?
        if (isset($css)) {
            foreach ($css as $style) {
                echo "<link rel='stylesheet' href='/public/css/$style'>";
            }
        }

        if (isset($js)) {
            foreach ($js as $script) {
                echo "<script src='/public/js/$script' type='module' defer></script>";
            }
        }


    ?>

    <script src="/public/js/toast.js" type="module" defer ></script>
</head>
<body>
    <div id="toast-container"></div>
    <nav class="navbar">
        <div class="logo font-extrabold">LinkinPurry</div>
    </nav>
    <main>

    <?= $content ?>
    </main>
</body>
</html>
