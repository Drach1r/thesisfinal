<?php
include 'header.php';
include 'sidebar.php';
$allowedUserTypes = array(1, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');
?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Edit Users

                    </h3>
                </div>
            </div>
        </div>

        <?php
        include 'db_connect.php';

        // Associative array to map database values to user types
        $userTypeMap = array(
            '1' => 'Admin',
            '2' => 'Production',
            '3' => 'Sales',
            '4' => 'Bookkeeper',
            '5' => 'Super Admin'
        );

        // Function to sanitize input data
        function sanitize($input)
        {
            global $conn;
            return mysqli_real_escape_string($conn, $input);
        }

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the edited data from the form
            $id = $_POST['id'];
            $name = sanitize($_POST['fname']);
            $email = sanitize($_POST['email']);
            $userType = sanitize($_POST['userType']);

            // Prepare the update statement
            $stmt = $conn->prepare("UPDATE users SET fname = ?, email = ?, userType = ? WHERE user_id = ?");

            // Bind the parameters to the statement
            $stmt->bind_param("sssi", $name, $email, $userType, $id);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success" role="alert">Data updated successfully.</div>';
                echo '<script>alert("Data updated successfully."); window.location.href = "users.php";</script>';
            } else {
                echo '<div class="alert alert-warning" role="alert">Error updating data: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Prepare the select statement
            $selectStmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $selectStmt->bind_param("i", $id);

            // Execute the select statement
            $selectStmt->execute();

            // Get the result
            $result = $selectStmt->get_result();

            // Fetch the user data
            $userData = $result->fetch_assoc();

            // Close the statement
            $selectStmt->close();
        }
        ?>

        <form name="editUser" method="POST" action="" enctype="multipart/form-data">
            <div class="card card-block">
                <div class="form-group row col-xs-6">
                    <input type="hidden" name="id" value="<?php echo $userData['user_id']; ?>">
                    <div class="form-group">
                        <label for="fname">Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $userData['fname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $userData['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="userType">User Type</label>
                        <select class="form-control" name="userType" id="userType" required>
                            <?php
                            // Populate the dropdown options using $userTypeMap
                            foreach ($userTypeMap as $value => $label) {
                                $selected = ($value == $userData['userType']) ? 'selected' : '';
                                echo "<option value=\"$value\" $selected>$label</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="users.php" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</article>

</body>

</html>