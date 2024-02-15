<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);

    // Extract arrays for multiple days
    $days = $_POST['day'];
    $time_ins = $_POST['time_in'];
    $time_outs = $_POST['time_out'];
    $labour_hours_arr = $_POST['labour_hours'];
    $salaries = $_POST['salary'];

    // Use prepared statement to prevent SQL injection
    $insertEmployeeStmt = $conn->prepare("INSERT INTO employee (name, position, day, time_in, time_out, labour_hours, salary) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Iterate through the arrays and insert each day's data
    for ($i = 0; $i < count($days); $i++) {
        $day = mysqli_real_escape_string($conn, $days[$i]);
        $time_in = mysqli_real_escape_string($conn, $time_ins[$i]);
        $time_out = mysqli_real_escape_string($conn, $time_outs[$i]);
        $labour_hours = mysqli_real_escape_string($conn, $labour_hours_arr[$i]);
        $salary = mysqli_real_escape_string($conn, $salaries[$i]);

        $insertEmployeeStmt->bind_param("sssssss", $name, $position, $day, $time_in, $time_out, $labour_hours, $salary);

        if ($insertEmployeeStmt->execute()) {
            echo '<div class="alert alert-success" role="alert">Employee data saved to the database.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
        }
    }

    $insertEmployeeStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>

<body>

    <article class="content items-list-page">
        <div class="title-search-block">
            <div class="title-block">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="title">
                            Add Employee
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="row form-group">
                            <div class="form-group col-xs-6">
                                <label for="name">Employee Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Employee Name" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="form-group col-xs-6">
                                <label for="position">Position</label>
                                <input type="text" class="form-control" id="position" name="position" placeholder="Position" required>
                            </div>
                        </div>

                        <!-- Multiple Days Input -->
                        <div id="days-container">
                            <div class="row form-group day-row">
                                <div class="form-group col-xs-4">
                                    <label for="day">Day</label>
                                    <input type="text" class="form-control" name="day[]" placeholder="Day" required>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="time_in">Time In</label>
                                    <input type="text" class="form-control" name="time_in[]" placeholder="Time in" required>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="time_out">Time Out</label>
                                    <input type="text" class="form-control" name="time_out[]" placeholder="Time out" required>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="labour_hours">Labour Hours</label>
                                    <input type="text" class="form-control" name="labour_hours[]" placeholder="Labour Hours" required>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="salary">Salary</label>
                                    <input type="text" class="form-control" name="salary[]" placeholder="Salary" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success" name="submit">Submit</button>
                            <a href="employee.php" class="btn btn-danger">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </article>

</body>

</html>