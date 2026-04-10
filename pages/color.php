<?php
    $number = $_POST['number'] ?? 0; 
    $number_of_colors = $_POST['number_of_colors'] ?? 0;
    $isValid = true;

    if ($number < 1 || $number > 26) {
        $isValid = false;
    }
    if ($number_of_colors < 1 || $number_of_colors > 10) {
        $isValid = false;
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
    <link rel="stylesheet" href="./style/color.css">
</head>
<body>
    <p>Enter the grid size and number of colors below to generate your coordinate sheets</p>
    <form class="inputForm" method="POST">
        <input type="hidden" name="page" value="color">
        <label>Rows & Columns (1-26):</label>
        <input type="number" min="1" max="26" name="number" required>
        <br>
        <label>Number of Colors (1-10):</label>
        <input type="number" min="1" max="10" name="number_of_colors" required>
        <br>
        <button type="submit">Generate</button>
        <br>
    </form>
    <div class="ColorDiv">
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <h1>Color Selection</h1>
        <form method="POST" target="_blank">
            <input type="hidden" name="page" value="print">
            <input type="hidden" name="number" value="<?= $number ?>">
            <input type="hidden" name="number_of_colors" value="<?= $number_of_colors ?>">
            <table class="colorlist">
            <?php for($i = 0; $i < $number_of_colors; $i++): ?>
                <tr>
                    <td class="left">
                        <select name="selectedColors[]">
                            <?php foreach($colors as $c): ?>
                                <option value="<?= $c ?>" <?= ($c === $colors[$i]) ? 'selected' : '' ?>>
                                    <?= $c ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="right">
                        <?php echo $colors[$i] ?>
                    </td>
                </tr>
            <?php endfor; ?>
            </table>
            <button type="submit" name="page" value="print">View Printable Version</button>
        </form>
        <?php endif; ?>
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
                        <td></td>
                    <?php endif; ?>                    
                <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
        
        </div>
</body>
</html>
