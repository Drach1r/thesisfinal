<?php

include 'header.php';
include 'sidebar.php';
include 'c_users.php';
$allowedUserTypes = array(1, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

    <style>
        /* Add your custom styles here */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <article class="content items-list-page">
        <div class="title-search-block">
            <div class="title-block">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="title">
                            Users
                            <a href="#" id="openModalBtn" class="btn btn-primary btn-sm rounded-s">
                                Add New
                            </a>
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

            // Check if the 'delete' parameter is provided in the URL
            if (isset($_GET['delete'])) {
                $deleteId = $_GET['delete'];

                // Prepare the delete statement
                $deleteStmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
                $deleteStmt->bind_param("i", $deleteId);

                // Execute the delete statement
                if ($deleteStmt->execute()) {
                    // Reset the auto increment value
                    $resetSql = "ALTER TABLE users AUTO_INCREMENT = 1";
                    mysqli_query($conn, $resetSql);

                    echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error deleting data: ' . $deleteStmt->error . '</div>';
                }

                // Close the statement
                $deleteStmt->close();
            }

            // Fetch data from the database
            $query = "SELECT * FROM users";
            $result = mysqli_query($conn, $query);

            // Check for errors in the query
            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            ?>

            <section class="row">
                <div class="card col-lg-12">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-title-body">

                            </div>
                            <section class="example">
                                <table class="table table-bordered col-md-12">
                                    <thead>
                                        <tr>

                                            <th class="text-center">Name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $id = $row['user_id'];
                                            $name = $row['fname'];
                                            $email = $row['email'];
                                            $type = $row['userType'];

                                            // Check if the user type is neither 1 (Admin) nor 5 (Super Admin) before rendering the row
                                            if ($type !== '1' && $type !== '5') {
                                                echo "<tr>";
                                                echo "<td class='text-center'>$name</td>";
                                                echo "<td class='text-center'>$email</td>";
                                                echo "<td class='text-center'>" . $userTypeMap[$type] . "</td>";
                                                echo "<td class='text-center'>";
                                                echo "<a href='edit_users.php?id=$id'><i class='fa fa-pencil'></i></a> | 
                                            <a href='?delete=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
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

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">

                <form name="item" method="POST" action="c_users.php">

                    <div class="card card-block">
                        <div class="form-group row">
                            <div class="form-group">
                                <label for="fname">Name</label>
                                <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter Full Name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                            </div>
                            <div class="form-group">
                                <label for="userType">Type</label>
                                <select class="form-control" name="userType" id="userType" required>
                                    <option value="" disabled selected>Select User Type</option>
                                    <option value="2">Production</option>
                                    <option value="3">Sales</option>
                                    <option value="4">Bookkeeper</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="users.php" class="btn btn-primary "> back </a>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>




        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        <script>
            // JavaScript to handle modal functionality
            document.getElementById('openModalBtn').addEventListener('click', function() {
                document.getElementById('myModal').style.display = 'flex';
            });

            document.getElementById('closeModalBtn').addEventListener('click', function() {
                document.getElementById('myModal').style.display = 'none';
            });

            // Close modal if the user clicks outside the modal
            window.addEventListener('click', function(event) {
                var modal = document.getElementById('myModal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        </script>
    </article>
</body>

</html>