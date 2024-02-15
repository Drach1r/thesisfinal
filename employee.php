<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $time_in = mysqli_real_escape_string($conn, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($conn, $_POST['time_out']);
    $labour_hours = mysqli_real_escape_string($conn, $_POST['labour_hours']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);

    // Use prepared statement to prevent SQL injection
    $insertEmployeeStmt = $conn->prepare("INSERT INTO employee (name, position, day, time_in, time_out, labour_hours, salary) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertEmployeeStmt->bind_param("sssssss", $name, $position, $day, $time_in, $time_out, $labour_hours, $salary);

    if ($insertEmployeeStmt->execute()) {
        echo '<div class="alert alert-success" role="alert">Employee data saved to the database.</div>';

        // Redirect to employee.php after successful submission
        header("Location: employee.php");
        exit(); // Make sure to exit after the header to prevent further execution
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
    }

    $insertEmployeeStmt->close();
}
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];

    // Prepare the delete statement for the "employee" table
    $deleteStmt = $conn->prepare("DELETE FROM employee WHERE id = ?");
    $deleteStmt->bind_param("i", $deleteId);

    // Execute the delete statement
    if ($deleteStmt->execute()) {
        // Check if any rows were affected by the delete operation
        if ($deleteStmt->affected_rows > 0) {
            echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
        } else {
            echo '<div class="alert alert-warning" role="alert">Deleting the record is not allowed.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting data: ' . $deleteStmt->error . '</div>';
    }

    // Close the statement
    $deleteStmt->close();

    $resetQuery = "ALTER TABLE employee AUTO_INCREMENT = 1";
    if ($conn->query($resetQuery) === TRUE) {
    } else {
        echo '<div class="alert alert-danger" role="alert">Error resetting auto-increment value: ' . $conn->error . '</div>';
    }
}



$query = "SELECT id, name, position, day, time_in, time_out, labour_hours, salary FROM employee";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
</head>

<body>
    <article class="content items-list-page">
        <div class="title-search-block">
            <div class="title-block">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="title">
                            Employee Management
                            <a href="c_employee.php" class="btn btn-primary btn-sm rounded-s" id="addProductBtn">
                                Add Employee
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
                        <div class="card-title-body"></div>
                        <section class="example">
                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Day</th>
                                        <th class="text-center">Time In</th>
                                        <th class="text-center">Time Out</th>
                                        <th class="text-center">Labour Hours</th>
                                        <th class="text-center">Salary</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        $position = $row['position'];
                                        $day = $row['day'];
                                        $time_in = $row['time_in'];
                                        $time_out = $row['time_out'];
                                        $labour_hours = $row['labour_hours'];
                                        $salary = $row['salary'];

                                        echo "<tr>";
                                        echo "<td class='text-center'>$name</td>";
                                        echo "<td class='text-center'>$position</td>";
                                        echo "<td class='text-center'>$day</td>";
                                        echo "<td class='text-center'>$time_in</td>";
                                        echo "<td class='text-center'>$time_out</td>";
                                        echo "<td class='text-center'>$labour_hours</td>";
                                        echo "<td class='text-center'>$salary</td>";
                                        echo "<td class='text-center'>
                                        <a href='edit_employee.php?id=$id'><i class='fa fa-pencil'></i></a> | 
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
        </div>
        </div>
    </article>



</body>

</html>