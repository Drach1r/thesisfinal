<?php


$query = "SELECT p.id, p.date, p.member_id, m.firstname, m.lastname, c.id AS carabaoId, c.name AS carabaoName, p.milkslip, p.actual
FROM produced p
INNER JOIN member m ON p.member_id = m.id
LEFT JOIN carabaos c ON p.carabao_id = c.id
ORDER BY p.date DESC"; // Use 'ORDER BY' to sort the results by date in descending order

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rowsPerPage = 10;
$startIndex = ($page - 1) * $rowsPerPage;
$data = array_slice($rows, $startIndex, $rowsPerPage);


header('Content-Type: application/json');
echo json_encode($data);
?>