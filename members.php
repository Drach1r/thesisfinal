<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

$allowedUserTypes = array(4, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

// Initialize $search, $limit, and $offset
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10; // Number of results per page limited to 10
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Modify your SQL query to include search condition, LIMIT, and OFFSET
$sql = "SELECT * FROM member WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Count the total number of rows for the search term
$totalRowsQuery = "SELECT COUNT(*) as total FROM member WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%'";

$totalRowsResult = mysqli_query($conn, $totalRowsQuery);


// Check if the query was successful before fetching the result
if ($totalRowsResult) {
  $totalRows = mysqli_fetch_assoc($totalRowsResult)['total'];

  // Calculate the total number of pages
  $totalPages = ceil($totalRows / $limit);
}

// Original query for the table with pending members (unchanged)
$pendingQuery = "SELECT member.id, member.lastname, member.firstname, member.address, member.stat
                  FROM member 
                  WHERE member.stat = 'Pending'
                  ORDER BY member.lastname ASC";

$pendingResult = mysqli_query($conn, $pendingQuery);

// Check for errors in the query
if (!$pendingResult) {
  die("Query failed: " . mysqli_error($conn));
}
?>

<article class="content items-list-page">
  <div class="title-search-block">
    <div class="title-block">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="title">
            Members List
            <a href="c_member.php" class="btn btn-primary btn-sm rounded-s">
              Register a new Member
            </a>
          </h3>
        </div>
      </div>
    </div>

    <section class="row">
      <div class="card col-lg-12">
        <div class="card-body">
          <div class="card-body">
            <br>
            <div class="card-title-body"> <strong>
                <h2>Pending Table:</h2>
              </strong></div>
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
                  // Iterate through each row of pending data (unchanged)
                  while ($row = mysqli_fetch_assoc($pendingResult)) {
                    $id = $row['id'];
                    $lname = $row['lastname'];
                    $fname = $row['firstname'];
                    $address = $row['address'];
                    $stat = $row['stat'];

                    echo "<tr>";
                    echo "<td class='text-center'>$lname</td>";
                    echo "<td class='text-center'>$fname</td>";
                    echo "<td class='text-center'>$address</td>";
                    echo "<td class='text-center'>$stat</td>";
                    echo "<td class='text-center'>
                            <a href='edit_member.php?id=$id'><i class='fa fa-pencil'></i></a> |
                            <a href='?delete=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>
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

    <br><br><br><br><br>

    <section class="row">
      <div class="card col-lg-12">
        <div class="card-body">
          <div class="card-body">
            <br>
            <br>
            <div class="card-title-body">
              <strong>
                <h2>Approved Table:</h2>
              </strong>
            </div>
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
                  // Modify the query to fetch only approved members with search condition
                  $searchCondition = !empty($search) ? "AND (member.firstname LIKE '%$search%' OR member.lastname LIKE '%$search%')" : "";
                  $approvedQuery = "SELECT member.id, member.lastname, member.firstname, member.address, member.stat
                                FROM member 
                                WHERE member.stat != 'Pending' $searchCondition
                                ORDER BY member.lastname ASC
                                LIMIT $limit OFFSET $offset";

                  $approvedResult = mysqli_query($conn, $approvedQuery);

                  // Check for errors in the query
                  if (!$approvedResult) {
                    die("Query failed: " . mysqli_error($conn));
                  }

                  // Iterate through each row of approved data
                  while ($row = mysqli_fetch_assoc($approvedResult)) {
                    $id = $row['id'];
                    $lname = $row['lastname'];
                    $fname = $row['firstname'];
                    $address = $row['address'];

                    echo "<tr>";
                    echo "<td class='text-center'>$lname</td>";
                    echo "<td class='text-center'>$fname</td>";
                    echo "<td class='text-center'>$address</td>";
                    echo "<td class='text-center'> <a href='edit_member.php?id=$id'><i class='fa fa-pencil'></i></a> | ";
                    echo "<a href='?delete=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </section>

            <nav class="text-xs-center">
              <ul class="pagination justify-content-center">
                <?php
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

          </div>
        </div>
      </div>
    </section>
  </div>
</article>

<?php include 'footer.php'; ?>