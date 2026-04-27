<?php
// require __DIR__ . '/db.php';

$result = $conn->query("SELECT id, name, hex_value FROM colors ORDER BY id");
$allColors = $result->fetch_all(MYSQLI_ASSOC);
$defaultColor = $allColors[0];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = $_POST['name'];
        $hex = $_POST['hex'];

        $sql = "SELECT COUNT(*) FROM colors WHERE hex_value = '$hex'";
        $name_res = $conn->query($sql);
        $name_count = $name_res->fetch_row()[0];
        $sql = "SELECT COUNT(*) FROM colors WHERE name = '$name'";
        $hex_res = $conn->query($sql);
        $hex_count = $hex_res->fetch_row()[0];

        if ($name_count != 0 || $hex_count != 0) {
            $errors[] = 'Cannot place duplicates';
        }

        if (!$errors){
            $sql = "INSERT INTO colors (name, hex_value) VALUES ('$name', '$hex')";
            $result = $conn->query($sql);
        }

    }

    if ($action === 'edit') {
        $name = $_POST['name'];
        $newHex = $_POST['newHex'];
        $newName = $_POST['newName'];



        $sql = "SELECT COUNT(*) FROM colors WHERE name = '$name'";
        $name_res = $conn->query($sql);

        /* if ($name_res != 0 || $hex_res != 0) {
            $errors[] = 'Cannot place duplicates';
        } */
        if (!$errors) {
            $sql = "UPDATE colors SET name = '$newName', hex_value = '$newHex' WHERE name = '$name'";
            $result = $conn->query($sql);
        }
    }

    if ($action === 'delete') {
        $name = $_POST['name'];

        $sql = 'SELECT COUNT(*) FROM colors';
        $count_res = $conn->query($sql);
        $color_count = $count_res->fetch_row()[0];

        if ($color_count <= 2) {
            $errors[] = 'Cannot Delete';
        }

        if (!$errors) {
            $sql = "DELETE FROM colors WHERE name = '$name'";
            $results = $conn->query($sql);
        }
    }
}
?>
<div class="header">
    <h1>Color Selection</h1>
    <p>Manage colors to use with the color coordinator</p>
</div>

<div class="color-add">
    <h2>Add a Color</h2>
    <form method="POST" action="?page=color-selection">
        <input type="hidden" name="page" value="color-selection">
        <label>Color Name:</label>
        <input type="text" name="name" required>
        <br>
        <label>Hex Value:</label>
        <input type="text" name="hex" required>
        <br>
        <button type="submit" name="action" value="add">Add Color</button>
    </form>
</div>

<div class="color-edit">
    <h2>Edit a Color</h2>
    <form method="POST" action="?page=color-selection">
        <input type="hidden" name="page" value="color-selection">
        <label>Select Color:</label>
        <select name="name" class="colorDropdown">
            <?php foreach($allColors as $c): ?>
            <option value="<?= htmlspecialchars($c['name']) ?>"
                <?= ($c['id'] === $defaultColor['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label>New Name:</label>
        <input type="text" name="newName" required>
        <br>
        <label>New Hex Value:</label>
        <input type="text" name="newHex" required>
        <br>
        <button type="submit" name="action" value="edit">Modify Color</button>
    </form>
</div>

<div class="color-remove">
    <h2>Delete a Color</h2>
    <form method="POST" action="?page=color-selection">
        <input type="hidden" name="page" value="color-selection">
        <select name="name" class="colorDropdown">
            <?php foreach($allColors as $c): ?>
            <option value="<?= htmlspecialchars($c['name']) ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="action" value="delete">Delete Color</button>
    </form>
</div>
