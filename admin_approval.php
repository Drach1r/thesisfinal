<?php
include 'db_connect.php';

function approveMember($conn, $memberId)
{
    $updateStatusStmt = $conn->prepare("UPDATE member SET stat = 'Approved' WHERE id = ?");
    $updateStatusStmt->bind_param("i", $memberId);
    $updateStatusStmt->execute();
    $updateStatusStmt->close();
}

// Handle member approval
if (isset($_GET['approve'])) {
    $approveId = $_GET['approve'];
    approveMember($conn, $approveId);

    // Redirect to the same page to refresh the data
    header("Location: pending_mem.php");
    exit();
}
