<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $carabao_id = $_POST["id"];

    // Fetch all the data for the carabao using the provided ID
    $carabao_data_query = $conn->prepare("SELECT * FROM carabaos WHERE id = ?");
    $carabao_data_query->bind_param("i", $carabao_id);
    $carabao_data_query->execute();
    $carabao_result = $carabao_data_query->get_result();

    // Fetch the member_name associated with the carabao
    $member_name = '';
    if ($carabao_row = $carabao_result->fetch_assoc()) {
        $member_id = $carabao_row['member_id'];

        $member_query = $conn->prepare("SELECT CONCAT(firstname, ' ', lastname) AS member_name FROM member WHERE id = ?");
        $member_query->bind_param("i", $member_id);
        $member_query->execute();
        $member_result = $member_query->get_result();

        if ($member_row = $member_result->fetch_assoc()) {
            $member_name = $member_row['member_name'];
        }
    }

    // Prepare the carabao data to be sent as JSON response
    $carabao_data = array(
        'id' => $carabao_row['id'],
        'member_name' => $member_name,
        'name' => $carabao_row['name'],
        'age' => $carabao_row['age'],
        'gender' => $carabao_row['gender']
    );

    header('Content-Type: application/json');
    echo json_encode($carabao_data);
}
