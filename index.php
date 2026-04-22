<?php 
$page = $_POST['page'] ?? 'index';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPushers Color Generator</title>
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/<?php echo $page; ?>.css">
</head>
<?php if ($page != 'print'): ?>
<header>
    <nav>
        <div class="div1">
            <img src = "images/Logo.jpg" alt = "PixelPushers Logo" class = "logo">
            <h1 id="TitleHeader">Welcome to the PixelPushers Color Generator!</h1>
        </div>
        <div class="div2">
            <ul>
                <form method="POST">
                    <li><button name="page" value="index">Home</button></li>
                    <li><button name="page" value="about">About Us</button></li>
                    <li><button name="page" value="color">Color Coordinator</button></li>
                    <li><button name="page" value="color-selection">Color Selection</button></li>
                </form>
            </ul>
        </div>
    </nav>
</header>
<?php endif; ?>
<body>
    <div class="divBody">
    <?php
        switch ($page) {
            case 'index':
                include './home.php';
                break;
            case 'about':
                include './about.php';
                break;
            case 'color':
                include './color.php';
                break;
            case 'color-selection':
                include './colors.php';
                break;
            case 'print':
                include './print.php';
                break;
        }
    ?>
    </div>
</body>
<?php if ($page != 'print'): ?>
<footer style="text-align: center; justify-content: center;">
    <p>&copy 2026 PixelPushers inc.</p>
</footer>
<?php endif; ?>
</html>