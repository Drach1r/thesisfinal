<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to update member status to 'Approved'
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

$limit = 10; // Number of results per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1
$offset = ($page - 1) * $limit; // Offset for the SQL query

$search = isset($_GET['search']) ? $_GET['search'] : ''; // Get the search term

// Initialize $totalRows to avoid undefined variable warning
$totalRows = 0;

// Fetch total rows for approved members
$searchConditionApproved = !empty($search) ? "AND (member.firstname LIKE '%$search%' OR member.lastname LIKE '%$search%')" : "";
$totalRowsQueryApproved = "SELECT COUNT(*) as total FROM member WHERE stat != 'Pending' $searchConditionApproved";
$totalRowsResultApproved = mysqli_query($conn, $totalRowsQueryApproved);

// Check if the query was successful before fetching the result
if ($totalRowsResultApproved) {
    $totalRows = mysqli_fetch_assoc($totalRowsResultApproved)['total'];

    // Calculate the total number of pages
    $totalPages = ceil($totalRows / $limit);
}

// Define $searchCondition based on whether a search term is provided
$searchConditionApproved = !empty($search) ? "AND (member.firstname LIKE '%$search%' OR member.lastname LIKE '%$search%')" : "";

// Fetch approved members
$approvedQuery = "SELECT member.id, member.lastname, member.firstname, member.address, member.stat
            FROM member 
            WHERE member.stat != 'Pending' $searchConditionApproved
            ORDER BY member.lastname ASC
            LIMIT $limit OFFSET $offset";

$approvedResult = mysqli_query($conn, $approvedQuery);

// Check for errors in the query
if (!$approvedResult) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch and display pending members
$pendingQuery = "SELECT member.id, member.lastname, member.firstname, member.address, member.stat
            FROM member 
            WHERE member.stat = 'Pending' $searchConditionApproved
            ORDER BY member.lastname ASC
            LIMIT $limit OFFSET $offset";

$pendingResult = mysqli_query($conn, $pendingQuery);

// Check for errors in the query
if (!$pendingResult) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Status</title>
</head>

<body>
    <article class="content items-list-page">
        <div class="title-search-block">
            <div class="title-block">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="title">
                            Manage Members
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="row">
            <br>
            <h3 class="title">Pending Members</h3>
            <br>
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                        </div>
                        <section class="example">
                            <br>

                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th class="text-center">Last Name</th>
                                        <th class="text-center">First Name</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch and display pending members
                                    while ($row = mysqli_fetch_assoc($pendingResult)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $row['lastname'] . "</td>";
                                        echo "<td class='text-center'>" . $row['firstname'] . "</td>";
                                        echo "<td class='text-center'>" . $row['address'] . "</td>";
                                        echo "<td class='text-center'>" . $row['stat'] . "</td>";
                                        echo "<td class='text-center'>
                                      <a href='view_member.php?id=" . $row['id'] . "'><i class='fa fa-eye'></i></a> |
                                   
                                      <a href='admin_approval.php?approve=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to approve this member?\")'>Approve</a>

                                    </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <br>
        <br>
        <br>




        <section class="row">
            <br>
            <h3 class="title">Approved Members</h3>
            <br>
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                        </div>

                        <br>
                        <form method="GET" action="">
                            <label for="search">Search:</label>
                            <input type="text" name="search" id="search" value="<?php echo $search; ?>">
                            <button type="submit" class="btn btn-primary  btn-sm rounded-s">Search</button>
                            <?php
                            // Display cancel search button if a search term is provided
                            if (!empty($search)) {
                                echo '<a href="?page=1" class="btn btn-warning  btn-sm rounded-s">Cancel Search</a>';
                            }
                            ?>
                        </form>
                        <br>
                        <section class="example">
                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th class="text-center">Last Name</th>
                                        <th class="text-center">First Name</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Iterate through each row of approved data
                                    while ($row = mysqli_fetch_assoc($approvedResult)) {
                                        $id = $row['id'];
                                        $lname = $row['lastname'];
                                        $fname = $row['firstname'];
                                        $address = $row['address'];
                                        $stat = $row['stat'];

                                        echo "<tr>";
                                        echo "<td class='text-center'>$lname</td>";
                                        echo "<td class='text-center'>$fname</td>";
                                        echo "<td class='text-center'>$address</td>";

                                        echo "<td class='text-center'> <a href='view_member.php?id=$id'><i class='fa fa-eye'></i></a>  ";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <nav class="text-xs-center">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    // Display "Previous" link
                                    if ($page > 1) {
                                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>&laquo; Previous</a></li>";
                                    } else {
                                        echo "<li class='page-item disabled'><span class='page-link'>&laquo; Previous</span></li>";
                                    }

                                    // Display pagination links
                                    for ($i = 1; $i <= $totalPages; $i++) {
                                        if ($i == $page) {
                                            echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
                                        }
                                    }

                                    // Display "Next" link
                                    if ($page < $totalPages) {
                                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next &raquo;</a></li>";
                                    } else {
                                        echo "<li class='page-item disabled'><span class='page-link'>Next &raquo;</span></li>";
                                    }
                                    ?>
                                </ul>

                            </nav>

                        </section>
                    </div>
                </div>
            </div>
        </section>
    </article>
</body>

</html>