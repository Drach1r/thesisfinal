<?php
include 'header.php';
include 'sidebar.php';

$allowedUserTypes = array(4, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

?>

<article class="content items-list-page">
    <div class="title-search-block">

        <div class="title-block">
            <div class="row">

                <div class="col-sm-6">
                    <h3 class="title">
                        Register Carabaos

                    </h3>

                </div>


            </div>

        </div>

        <?php
        include 'db_connect.php';

        function insertproducedData($conn)
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $member_id = $_POST["member_id"];
                $name = $_POST["name"];
                $age = $_POST["age"];
                $gender = $_POST["gender"];




                $insertStmt = $conn->prepare("INSERT INTO carabaos (member_id, name, age, gender) VALUES (?, ?, ?, ?)");
                $insertStmt->bind_param("ssss", $member_id, $name, $age, $gender);


                if ($insertStmt->execute()) {
                    echo '<div class="alert alert-success" role="alert">Data inserted successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error inserting data: ' . $insertStmt->error . '</div>';
                }

                $insertStmt->close();
            }
        }


        insertproducedData($conn);
        ?>


        <html>

        <body>
            <form name="item" method="POST" action="">
                <div class="card card-block">
                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="member_id">Cooperator</label>
                            <select class="form-control" name="member_id" id="member_id" required>
                                <option value=""> --- SELECT ---</option>
                                <?php
                                $members = $conn->query("SELECT * FROM member ORDER BY id");
                                while ($row = $members->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . ' - ' . $row['carabao'] . '</option>';
                                }
                                ?>
                            </select>

                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="name">Carabao Tag</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Tag " required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="birthday">Birthday</label>
                            <input type="date" class="form-control" name="birthday" id="birthday" placeholder="Enter Birthdate" required>
                        </div>
                        <div class="form-group col-xs-1">
                            <label for="age">Age</label>
                            <input type="text" class="form-control" name="age" id="age" placeholder="" required readonly>
                        </div>
                    </div>


                    <div class="row form-group">
                        <div class="form-group col-xs-3">
                            <label for="gender">Gender</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="" disabled selected>-Select Gender-</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class=" row form-group " style="float: right">
                        <a href="carabao.php" class="btn btn-danger">
                            Back
                        </a>
                        <button type="submit" class="btn btn-success">Submit</button>

                    </div>


                </div>

                <script>
                    document.getElementById("birthday").addEventListener("change", function() {
                        var birthday = new Date(this.value);
                        var today = new Date();
                        var age = today.getFullYear() - birthday.getFullYear();


                        var birthMonth = birthday.getMonth();
                        var currentMonth = today.getMonth();
                        if (currentMonth < birthMonth || (currentMonth === birthMonth && today.getDate() < birthday.getDate())) {
                            age--;
                        }

                        document.getElementById("age").value = age;
                    });
                </script>




    </div>
    </form>

    </body>

    </html>