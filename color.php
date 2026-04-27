<?php
    $number = $_POST['number'] ?? null; 
    $number_of_colors = $_POST['number_of_colors'] ?? null;
    $isValid = true;
    
    $errors = [];
    
    if (isset($_POST['x'])) {
        if ($number < 1 || $number > 26) {
            $errors[] = "Number of Rows and Columns not in range !!!";
            $isValid = false;
        }
        if ($number_of_colors < 1 || $number_of_colors > 10) {
            $errors[] = "Number of columns not in range !!!";
            $isValid = false;
        }
    }

    $colors = ["Red", "Orange", "Yellow", "Green", "Blue", "Purple", "Grey", "Brown", "Black", "Teal"];
    $alphabet = range('A', 'Z');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COLOR PAGE</title>
    <link rel="stylesheet" href="./style/color.css">
</head>
<body>
    <p>Enter the grid size and number of colors below to generate your coordinate sheets</p>
    <form class="inputForm" method="POST">
        <input type="hidden" name="page" value="color">
        <label>Rows & Columns (1-26):</label>
        <input type="number" name="number" required>
        <br>
        <label>Number of Colors (1-10):</label>
        <input type="number" name="number_of_colors" required>
        <br>
        <button type="submit" name="x">Generate</button>
        <br>
    </form>
    <div class="ColorDiv">
    <?php if (isset($_POST['x']) && $isValid): ?>
        <h1>Color Selection</h1>
            <table class="colorlist">
            <?php for($i = 0; $i < $number_of_colors; $i++): ?>
                <tr>
        <td>
            <input type="radio" name="activeColor"
                   value="<?= $colors[$i] ?>"
                   <?= $i === 0 ? 'checked' : '' ?>>
        </td>

        <td>
            <select class="colorDropdown" data-index="<?= $i ?>">
                <?php foreach($colors as $c): ?>
                    <option value="<?= $c ?>" <?= ($c === $colors[$i]) ? 'selected' : '' ?>>
                        <?= $c ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>

        <td class="coordList" id="coords-<?= $i ?>"></td>
    </tr>
<?php endfor; ?>
</table>

<div id="errorMsg"></div>
        <h1>Coordinate Grid</h1>
        <table class="grid">
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
                    <?php if ($n != 0 && $col != 0 && $col <= $number): ?>
                        <td class="paintCell"
                            data-coord="<?= $alphabet[$col - 1] . $n ?>">
                        </td>
                    <?php endif; ?>                    
                <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
        <form method="POST" action="?page=print" target="_blank">
            <input type="hidden" name="page" value="print">
            <input type="hidden" name="number" value="<?= $number ?>">
            <input type="hidden" name="number_of_colors" value="<?= $number_of_colors ?>">
            <button type="submit" name="page" value="print">View Printable Version</button>
        </form>
        </div>
        <?php endif; ?>
        <?php if (isset($_POST['x']) && !$isValid): ?>
            <?php foreach($errors as $e): ?>
                <?php echo $e; ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>


<script>
let radios = document.querySelectorAll('input[name="activeColor"]');
let dropdowns = document.querySelectorAll('.colorDropdown');

let activeColor = document.querySelector('input[name="activeColor"]:checked').value;

let colorMap = {};
let previousValues = [];


dropdowns.forEach((dd, i) => {
    previousValues[i] = dd.value;
    colorMap[dd.value] = [];
});

radios.forEach(r => {
    r.addEventListener('change', () => {
        activeColor = r.value;
    });
});


document.querySelectorAll('.paintCell').forEach(cell => {
    cell.addEventListener('click', () => {
        let coord = cell.dataset.coord;

        cell.style.backgroundColor = activeColor;

        if (!colorMap[activeColor].includes(coord)) {
            colorMap[activeColor].push(coord);
        }

        updateCoords();
    });
});

function updateCoords() {
    dropdowns.forEach((dd, i) => {
        let color = dd.value;
        let coords = colorMap[color] || [];

        coords.sort((a, b) => {
            let L1 = a[0], N1 = parseInt(a.slice(1));
            let L2 = b[0], N2 = parseInt(b.slice(1));

            if (L1 === L2) return N1 - N2;
            return L1.localeCompare(L2);
        });

        document.getElementById("coords-" + i).innerText = coords.join(", ");
    });
}

dropdowns.forEach((dd, i) => {
    dd.addEventListener('change', () => {
        let newColor = dd.value;

        let used = Array.from(dropdowns).map(d => d.value);
        let count = used.filter(c => c === newColor).length;

        if (count > 1) {
            dd.value = previousValues[i];
            showError("Each color can only be used once.");
            return;
        }

        let oldColor = previousValues[i];

        colorMap[newColor] = colorMap[oldColor] || [];
        delete colorMap[oldColor];

        document.querySelectorAll('.paintCell').forEach(cell => {
            if (cell.style.backgroundColor === oldColor) {
                cell.style.backgroundColor = newColor;
            }
        });

        previousValues[i] = newColor;
        clearError();
        updateCoords();
    });
});

function showError(msg) {
    document.getElementById("errorMsg").innerText = msg;
}

function clearError() {
    document.getElementById("errorMsg").innerText = "";
}
</script>
</body>
</html>
