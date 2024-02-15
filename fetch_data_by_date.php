<?php
include 'db_connect.php';

if (isset($_GET['date'])) {
    $selectedDate = mysqli_real_escape_string($conn, $_GET['date']);

    $query = "SELECT p.date, m.firstname, m.lastname, c.name AS carabaoName, p.milkslip, p.actual
              FROM produced p
              INNER JOIN member m ON p.member_id = m.id
              LEFT JOIN carabaos c ON p.carabao_id = c.id
              WHERE p.date = '$selectedDate'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $date = $row['date'];
        $member = $row['firstname'] . ' ' . $row['lastname'];
        $carabaoName = $row['carabaoName'];
        $milkslip = $row['milkslip'];
        $actual = $row['actual'];

        $data[] = array(
            'Date Received' => $date,
            'Cooperator' => $member,
            'Carabao ID' => $carabaoName,
            'Actual' => $actual,
            'Milk Slip' => $milkslip
        );
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo "No date selected.";
}
?>
