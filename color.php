<?php
    require_once 'db.php';

    $number = $_POST['number'] ?? null; 
    $number_of_colors = $_POST['number_of_colors'] ?? null;
    $isValid = true;
    $errors = [];

    $result = $conn->query("SELECT id, name, hex_value FROM colors ORDER BY id");
    $allColors = $result->fetch_all(MYSQLI_ASSOC);
    $maxColors = count($allColors);
    
  
    if (isset($_POST['x'])) {
        if ($number < 1 || $number > 26) {
            $errors[] = "Number of Rows and Columns not in range (1-26)!";
            $isValid = false;
        }
        if ($number_of_colors < 1 || $number_of_colors > $maxColors) {
            $errors[] = "Number of colors not in range (1-$maxColors)!";
            $isValid = false;
        }
    }

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
        <label>Number of Colors (1-<?= $maxColors ?>):</label>
        <input type="number" name="number_of_colors" required>
        <br>
        <button class="rounded-btn" type="submit" name="x">Generate</button>
        <br>
    </form>

    <div class="ColorDiv">
    <?php if (isset($_POST['x']) && $isValid): ?>
        <h1>Color Selection</h1>
        <table class="colorlist">
        <?php for($i = 0; $i < $number_of_colors; $i++):
            $defaultColor = $allColors[$i]; ?>
            <tr>
                <td>
                    <input type="radio" name="activeColor"
                           value="<?= $defaultColor['hex_value'] ?>"
                           <?= $i === 0 ? 'checked' : '' ?>>
                </td>
                <td>
                    <select class="colorDropdown" data-index="<?= $i ?>">
                        <?php foreach($allColors as $c): ?>
                            <option value="<?= $c['hex_value'] ?>"
                                    <?= ($c['id'] === $defaultColor['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
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
                    <?php elseif ($n === 0): ?>
                        <td><?= $alphabet[$col - 1] ?></td>
                    <?php elseif ($col === 0): ?>
                        <td><?= $n ?></td>
                    <?php else: ?>
                        <td class="paintCell" data-coord="<?= $alphabet[$col - 1] . $n ?>"></td>
                    <?php endif; ?>
                <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>

        <form method="POST" action="?page=print" target="_blank" id="printForm">
        <input type="hidden" name="page" value="print">
        <input type="hidden" name="number" value="<?= $number ?>">
        <input type="hidden" name="number_of_colors" value="<?= $number_of_colors ?>">
        <button class="rounded-btn" type="button" onclick="submitPrintForm()">View Printable Version</button>
</form>

    <?php endif; ?>
    <?php if (isset($_POST['x']) && !$isValid): ?>
        <?php foreach($errors as $e): ?>
            <?= htmlspecialchars($e) ?><br>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>

<script>
let radios = document.querySelectorAll('input[name="activeColor"]');
let dropdowns = document.querySelectorAll('.colorDropdown');

if (radios.length > 0) {
    let activeColor = document.querySelector('input[name="activeColor"]:checked').value;
    let colorMap = {};
    let previousValues = [];

    dropdowns.forEach((dd, i) => {
        previousValues[i] = dd.value;
        colorMap[dd.value] = [];
    });

    radios.forEach(r => {
        r.addEventListener('change', () => { activeColor = r.value; });
    });

    document.querySelectorAll('.paintCell').forEach(cell => {
        cell.addEventListener('click', () => {
            let coord = cell.dataset.coord;
            cell.style.backgroundColor = activeColor;

            Object.keys(colorMap).forEach(hex => {
                colorMap[hex] = colorMap[hex].filter(c => c !== coord);
            });

            if (!colorMap[activeColor]) colorMap[activeColor] = [];
            colorMap[activeColor].push(coord);
            updateCoords();
        });
    });

    function updateCoords() {
        dropdowns.forEach((dd, i) => {
            let coords = colorMap[dd.value] || [];
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
            let newHex = dd.value;
            let used = Array.from(dropdowns).map(d => d.value);

            if (used.filter(c => c === newHex).length > 1) {
                dd.value = previousValues[i];
                showError("Each color can only be used once.");
                return;
            }

            let oldHex = previousValues[i];
            colorMap[newHex] = colorMap[oldHex] || [];
            delete colorMap[oldHex];

            document.querySelectorAll('.paintCell').forEach(cell => {
                if (cell.style.backgroundColor === hexToRgb(oldHex)) {
                    cell.style.backgroundColor = newHex;
                }
            });

            radios[i].value = newHex;
            if (activeColor === oldHex) activeColor = newHex;

            previousValues[i] = newHex;
            clearError();
            updateCoords();
        });
    });

    function hexToRgb(hex) {
        let r = parseInt(hex.slice(1,3), 16);
        let g = parseInt(hex.slice(3,5), 16);
        let b = parseInt(hex.slice(5,7), 16);
        return `rgb(${r}, ${g}, ${b})`;
    }

    function showError(msg) { document.getElementById("errorMsg").innerText = msg; }
    function clearError() { document.getElementById("errorMsg").innerText = ""; }
}
</script>

<script>
function submitPrintForm() {
    const form = document.getElementById('printForm');

    form.querySelectorAll('.print-data').forEach(el => el.remove());

    dropdowns.forEach((dd, i) => {
        const selectedOption = dd.options[dd.selectedIndex];

        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'colorNames[]';
        nameInput.value = selectedOption.text;
        nameInput.className = 'print-data';
        form.appendChild(nameInput);

        const hexInput = document.createElement('input');
        hexInput.type = 'hidden';
        hexInput.name = 'colorHexes[]';
        hexInput.value = dd.value;
        hexInput.className = 'print-data';
        form.appendChild(hexInput);

        const coordInput = document.createElement('input');
        coordInput.type = 'hidden';
        coordInput.name = 'coordLists[]';
        coordInput.value = document.getElementById('coords-' + i).innerText;
        coordInput.className = 'print-data';
        form.appendChild(coordInput);
    });

    form.submit();
}
</script>
</body>
</html>
