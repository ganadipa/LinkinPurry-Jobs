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
            if (isset($title)) {
                echo $title;
            } else {
                echo "LinkinPurry";
            }
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
    <?= View::render('Partial', 'Navbar', [
            'user' => null
        ]) ?>
    <div id="toast-container"></div>
    
    <main>

    <?= $content ?>
    </main>
</body>
</html>
