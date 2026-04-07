<?php 
$page = $_POST['page'] ?? 'index';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPushers Color Generator</title>
    <link rel="stylesheet" href="./style/about.css">
</head>
<header>
    <nav>
        <div class="div1">
            <img src = "images/Logo.jpg" alt = "PixelPushers Logo" class = "logo">
            <h1 id="TitleHeader">Welcome to the PixelPushers Color Generator!</h1>
        </div>
        <div class="div2">
            <ul>
                <form method="POST">
                    <button name="page" value="index">Home</button>
                    <button name="page" value="about">About Us</button>
                    <button name="page" value="color">Color Coordinator</button>
                </form>
            </ul>
        </div>
    </nav>
</header>
<body>
    <?php
        switch ($page) {
            case 'index':
                include 'pages/home.php';
                break;
            case 'about':
                include 'pages/about.php';
                break;
            case 'color':
                include 'pages/color.php';
                break;
        }
    ?>
</body>
</html>