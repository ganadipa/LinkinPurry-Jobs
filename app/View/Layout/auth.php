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
    <link rel="stylesheet" href="/public/css/globals.css">
    <link rel="icon" href="/public/favicon.ico" type="image/x-icon">

    <?
        if (isset($css)) {
            foreach ($css as $style) {
                echo "<link rel='stylesheet' href='/public/css/$style'>";
            }
        }

        if (isset($js)) {
            foreach ($js as $script) {
                echo "<script src='/public/js/$script'></script>";
            }
        }


    ?>
</head>
<body>
    <nav class="navbar">
        <div class="logo font-extrabold">LinkinPurry</div>
    </nav>
    <main>

    <?= $content ?>
    </main>
</body>
</html>
