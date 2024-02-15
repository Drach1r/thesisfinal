<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["member_id"])) {
    $member_id = $_POST["member_id"];

    $carabaos = $conn->query("SELECT * FROM carabaos WHERE member_id = $member_id ORDER BY id");
    $carabao_data = array();
    while ($row = $carabaos->fetch_assoc()) {
        $carabao_data[] = array(
            'id' => $row['id'],
            'name' => $row['name']
        );
    }

    header('Content-Type: application/json');
    echo json_encode($carabao_data);
}
