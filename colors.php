<?php
require __DIR__ . '/db.php';


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = $_POST['name'];
        $hex = $_POST['hex'];

        $sql = 'SELECT COUNT(*) FROM colors WHERE hex = $hex';
        $name_res = $conn->query($sql);
        $sql = 'SELECT COUNT(*) FROM colors WHERE name = $name';
        $hex_res = $conn->query($sql);

        if ($name_res != 0 || $hex_res != 0) {
            $errors[] = 'Cannot place duplicates';
        }

        if (!errors){
            $sql = "INSERT INTO colors VALUES ('$name', '$hex')";
            $result = $conn->query($sql);
        }

    }

    if ($action === 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $hex = $_POST['hex'];
        
        $sql = 'SELECT COUNT(*) FROM colors WHERE hex = $hex';
        $name_res = $conn->query($sql);
        $sql = 'SELECT COUNT(*) FROM colors WHERE name = $name';
        $hex_res = $conn->query($sql);

        if ($name_res != 0 || $hex_res != 0) {
            $errors[] = 'Cannot place duplicates';
        }
        if (!$errors) {
            $sql = 'UPDATE color SET name = $name, hex_value = $hex WHERE id = $id';
            $result = $conn->query($sql);
        }
    }

    if ($action === 'delete') {
        $name = $_POST['name'];

        $sql = 'SELECT COUNT(*) FROM colors';
        $result = $conn->query($sql);

        if ($result <= 2) {
            $errors[] = 'Cannot Delete';
        }

        if (!$errors) {
            $sql = "DELETE FROM colors WHERE name = $name";
            $results = $conn->query($sql);
        }
    }
}
?>
