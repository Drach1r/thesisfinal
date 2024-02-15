<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';
$allowedUserTypes = array(4, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

// Check if the 'delete' parameter is provided in the URL
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];

    // Prepare the delete statement for the carabaos table
    $deleteCarabaoStmt = $conn->prepare("DELETE FROM carabaos WHERE id = ?");
    $deleteCarabaoStmt->bind_param("i", $deleteId);

    // Execute the delete statement for carabaos table
    if ($deleteCarabaoStmt->execute()) {
        echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting data: ' . $deleteCarabaoStmt->error . '</div>';
    }

    // Close the statement for carabaos table
    $deleteCarabaoStmt->close();
}

// Pagination and Search logic
$limit = 5; // Number of results per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Check if search term is provided
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch data from the database with Last Name in alphabetical order and join with member table to get the full member name
$query = "SELECT carabaos.id, CONCAT(member.firstname, ' ', member.lastname) AS member_name, carabaos.name, carabaos.age, carabaos.gender
              FROM carabaos 
              LEFT JOIN member ON carabaos.member_id = member.id
              WHERE CONCAT(member.firstname, ' ', member.lastname) LIKE '%$search%'
              ORDER BY member_name ASC
              LIMIT $limit OFFSET $offset"; // Apply LIMIT and OFFSET for pagination
$result = mysqli_query($conn, $query);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch total rows for pagination with search
$totalRowsQuery = "SELECT COUNT(*) as total FROM carabaos
                   LEFT JOIN member ON carabaos.member_id = member.id
                   WHERE CONCAT(member.firstname, ' ', member.lastname) LIKE '%$search%'";
$totalRowsResult = mysqli_query($conn, $totalRowsQuery);

// Check if the query was successful before fetching the result
if ($totalRowsResult) {
    $totalRows = mysqli_fetch_assoc($totalRowsResult)['total'];
}

?>



<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Carabaos List
                        <a href="c_carabao.php" class="btn btn-primary btn-sm rounded-s">
                            Register a new Carabao
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>


    <section class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <div class="card-body">
                    <div class="card-title-body">
                        <br>
                        <form method="GET" action="">
                            <label for="search">Search:</label>
                            <input type="text" name="search" id="search" value="<?php echo $search; ?>">
                            <button type="submit" class="btn btn-primary btn-sm rounded-s">Search</button>
                            <?php
                            // Display cancel search button if a search term is provided
                            if (!empty($search)) {
                                echo '<a href="?page=1" class="btn btn-warning btn-sm rounded-s">Cancel Search</a>';
                            }
                            ?>
                        </form>
                        <br>
                    </div>
                    <section class="example">
                        <table class="table table-bordered col-md-12">
                            <thead>
                                <tr>
                                    <th class="text-center">Owner Name</th>
                                    <th class="text-center">Carabao Tag</th>
                                    <th class="text-center">Age</th>
                                    <th class="text-center">Gender</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Iterate through each row of data
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    $member_name = $row['member_name'];
                                    $name = $row['name'];
                                    $age = $row['age'];
                                    $gender = $row['gender'];

                                    echo "<tr>";
                                    echo "<td class='text-center'>$member_name</td>";
                                    echo "<td class='text-center'>$name</td>";
                                    echo "<td class='text-center'>$age</td>";
                                    echo "<td class='text-center'>$gender</td>";
                                    echo "<td class='text-center'>
                                            <a href='edit_carabao.php?id=$id'><i class='fa fa-pencil'></i></a> | 
                                                <a href='?delete=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </section>
                    <nav class="text-xs-center">
                        <ul class="pagination justify-content-center">
                            <div class="pagination">
                                <?php
                                // Previous page link
                                if ($page > 1) {
                                    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>&laquo; Previous</a></li>";
                                } else {
                                    echo "<li class='page-item disabled'><span class='page-link'>&laquo; Previous</span></li>";
                                }

                                // Page links
                                for ($i = 1; $i <= ceil($totalRows / $limit); $i++) {
                                    echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
                                }

                                // Next page link
                                if ($page < ceil($totalRows / $limit)) {
                                    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next &raquo;</a></li>";
                                } else {
                                    echo "<li class='page-item disabled'><span class='page-link'>Next &raquo;</span></li>";
                                }
                                ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        </div>
    </section>
</article>


</html>