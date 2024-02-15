<!-- print_receipt.php -->

<?php
include 'db_connect.php';

if (isset($_GET['member_id'])) {
    $memberID = $_GET['member_id'];

    // Get the member's information
    $queryMember = "SELECT firstname, lastname FROM member WHERE id = '$memberID'";
    $resultMember = mysqli_query($conn, $queryMember);
    $rowMember = mysqli_fetch_assoc($resultMember);

    // Get the member's transactions for the current week with calculated purchase price
    $queryTransactions = "SELECT p.actual, p.date, c.name AS carabao_name,
                          (p.actual * rm.price) AS total_purchase
                          FROM produced p
                          INNER JOIN carabaos c ON p.carabao_id = c.id
                          INNER JOIN rawmaterials rm ON rm.RawMaterialID = 205
                          WHERE p.member_id = '$memberID' AND WEEK(p.date) = WEEK(CURDATE())";
    $resultTransactions = mysqli_query($conn, $queryTransactions);

    // Initialize total purchase
    $totalPurchase = 0;

    // HTML content for the receipt
    echo '<h2>Weekly Transactions Receipt</h2>';
    echo '<p><strong>Member Name:</strong> ' . $rowMember['firstname'] . ' ' . $rowMember['lastname'] . '</p>';
    echo '<p><strong>Week:</strong> ' . date('Y-m-d', strtotime('last Sunday')) . ' - ' . date('Y-m-d', strtotime('next Saturday')) . '</p>';

    // Table for transactions
    echo '<table border="1">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Date</th>';
    echo '<th>Carabao Name</th>';
    echo '<th>Actual Amount</th>';
    echo '<th>Total Purchase</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    while ($rowTransaction = mysqli_fetch_assoc($resultTransactions)) {
        echo '<tr>';
        echo '<td>' . $rowTransaction['date'] . '</td>';
        echo '<td>' . $rowTransaction['carabao_name'] . '</td>';
        echo '<td>' . $rowTransaction['actual'] . '</td>';
        echo '<td>' . $rowTransaction['total_purchase'] . '</td>';
        echo '</tr>';

        // Update total purchase
        $totalPurchase += $rowTransaction['total_purchase'];
    }
    // Display total purchase in the same table
    echo '<tr>';
    echo '<td colspan="3"><strong>Total Purchase for the Week</strong></td>';
    echo '<td>' . $totalPurchase . '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
} else {
    // Handle the case when member ID is not provided
    echo 'Invalid member ID';
}
?>