<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];

    // Fetch existing employee data for editing
    $selectEmployeeStmt = $conn->prepare("SELECT * FROM employee WHERE id = ?");
    $selectEmployeeStmt->bind_param("i", $employeeId);
    $selectEmployeeStmt->execute();
    $result = $selectEmployeeStmt->get_result();

    if ($result->num_rows > 0) {
        $employeeData = $result->fetch_assoc();

        // Initialize variables with existing data for pre-filling the form
        $name = $employeeData['name'];
        $position = $employeeData['position'];
        $days = explode(",", $employeeData['day']);
        $time_ins = explode(",", $employeeData['time_in']);
        $time_outs = explode(",", $employeeData['time_out']);
        $labour_hours_arr = explode(",", $employeeData['labour_hours']);
        $salaries = explode(",", $employeeData['salary']);
    } else {
        echo '<div class="alert alert-danger" role="alert">Employee not found.</div>';
        exit();
    }

    $selectEmployeeStmt->close();
} else {
    echo '<div class="alert alert-danger" role="alert">Employee ID not provided.</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);

    // Extract array for a single day
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $time_in = mysqli_real_escape_string($conn, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($conn, $_POST['time_out']);
    $labour_hours = mysqli_real_escape_string($conn, $_POST['labour_hours']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);

    // Use prepared statement to update employee data
    $updateEmployeeStmt = $conn->prepare("UPDATE employee SET name = ?, position = ?, day = ?, time_in = ?, time_out = ?, labour_hours = ?, salary = ? WHERE id = ?");
    $updateEmployeeStmt->bind_param("sssssssi", $name, $position, $day, $time_in, $time_out, $labour_hours, $salary, $employeeId);

    // Update the single day's data
    $updateEmployeeStmt->execute();

    $updateEmployeeStmt->close();
    echo '<div class="alert alert-success" role="alert">Employee data updated successfully.</div>';
}
?>
<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Edit Employee
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
                            <input type="text" class="form-control" id="name" name="name" placeholder="Employee Name" value="<?php echo $name; ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="position" name="position" placeholder="Position" value="<?php echo $position; ?>" required>
                        </div>
                    </div>

                    <!-- Single Day Input -->
                    <div class="row form-group day-row">
                        <div class="form-group col-xs-4">
                            <label for="day">Day</label>
                            <input type="text" class="form-control" name="day" placeholder="Day" value="<?php echo $days[0]; ?>" required>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="time_in">Time In</label>
                            <input type="text" class="form-control" name="time_in" placeholder="Time in" value="<?php echo $time_ins[0]; ?>" required>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="time_out">Time Out</label>
                            <input type="text" class="form-control" name="time_out" placeholder="Time out" value="<?php echo $time_outs[0]; ?>" required>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="labour_hours">Labour Hours</label>
                            <input type="text" class="form-control" name="labour_hours" placeholder="Labour Hours" value="<?php echo $labour_hours_arr[0]; ?>" required>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="salary">Salary</label>
                            <input type="text" class="form-control" name="salary" placeholder="Salary" value="<?php echo $salaries[0]; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <a href="employee.php" class="btn btn-primary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</article>

</body>

</html>