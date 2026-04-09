<?php
    $number = $_POST['number'] ?? 0; 
    $number_of_colors = $_POST['number_of_colors'] ?? 0;

    if ($number < 1 || $number > 26) {
        echo "INVALID";
    }
    if ($number_of_colors < 1 || $number_of_colors > 10) {
        echo "INVALID";
    }

    $colors = ["Red", "Orange", "Yellow", "Green", "Blue", "Purple", "Grey", "Brown", "Black", "Teal"];
    $alphabet = range('A', 'Z');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TITLE</title>
    <link rel="stylesheet" href="./style/print.css">
</head>
<body>
    <img src = "images/Logo.jpg" alt = "PixelPushers Logo" class = "logo">

    <h1>PixelPushers</h1>
    <p>Color Selector Company</p>
    <h1>Color Selection</h1>
        <table>
            <?php for($i = 0; $i < $number_of_colors; $i++): ?>
                <tr>
                    <td>
                        <select>
                            <?php foreach($colors as $c): ?>
                                <option value="<?php echo $c; ?>">
                                    <?php echo $c; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <?php echo $colors[$i] ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
        <h1>Coordinate Grid</h1>
        <table>
            <?php for($n = 0; $n < $number + 1; $n++): ?>
                <tr>
                <?php for($col = 0; $col < $number + 1; $col++): ?>
                    <?php if($n === 0 && $col === 0): ?>
                        <td> </td>
                    <?php endif; ?>
                    <?php if ($n === 0 && $col != 0): ?>
                        <td> <?php echo $alphabet[$col - 1] ?>
                    <?php endif; ?>
                    <?php if ($col === 0 && $n != 0): ?>
                        <td><?php echo $n ?>
                    <?php endif; ?>
                    <?php if ($n != 0 && $col < $number): ?>
                        <td> 0 </td>
                    <?php endif; ?>                    
                <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
    
</body>
</html>