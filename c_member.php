<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';
$allowedUserTypes = array(4, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');
?>

<article class="content items-list-page">
    <div class="title-search-block">

        <div class="title-block">
            <div class="row">

                <div class="col-sm-6">
                    <h3 class="title">
                        Register Cooperator
                    </h3>
                </div>
                <br><br>
            </div>
        </div>
        <?php

        function sanitize($data)
        {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = sanitize($_POST['firstname']);
            $lastname = sanitize($_POST['lastname']);
            $age = sanitize($_POST['age']);
            $status = sanitize($_POST['status']);
            $gender = sanitize($_POST['gender']);
            $religion = sanitize($_POST['religion']);
            $birthday = sanitize($_POST['birthday']);
            $work = sanitize($_POST['work']);
            $address = sanitize($_POST['address']);
            $education = sanitize($_POST['education']);
            $contact = sanitize($_POST['contact']);
            $tin = sanitize($_POST['tin']);
            $dateApplied = sanitize($_POST['dateApplied']);
            $n_emergency = sanitize($_POST['n_emergency']);
            $relation = sanitize($_POST['relation']);
            $cn_emergency = sanitize($_POST['cn_emergency']);
            $stat = sanitize($_POST['stat']);

            $stmt = $conn->prepare("INSERT INTO member (firstname, lastname, age, status, gender, religion, birthday, work, address, education, contact, tin, dateApplied, n_emergency, relation, cn_emergency, stat) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssssssss", $firstname, $lastname, $age, $status, $gender, $religion, $birthday, $work, $address, $education, $contact,  $tin, $dateApplied,  $n_emergency, $relation, $cn_emergency, $stat);

            if ($stmt->execute()) {
                $member_id = $stmt->insert_id;

                // Set initial status to 'pending'
                $initialStatus = 'Pending';
                $updateStatusStmt = $conn->prepare("UPDATE member SET stat = ? WHERE id = ?");
                $updateStatusStmt->bind_param("si", $initialStatus, $member_id);
                $updateStatusStmt->execute();
                $updateStatusStmt->close();

                if (isset($_FILES['photo'])) {
                    $files = $_FILES['photo'];

                    for ($i = 0; $i < count($files['name']); $i++) {
                        $file_name = $files['name'][$i];
                        $file_tmp = $files['tmp_name'][$i];
                        $file_size = $files['size'][$i];
                        $file_error = $files['error'][$i];

                        if ($file_error === UPLOAD_ERR_OK) {
                            $destination = 'assets/files/' . $file_name;

                            if (move_uploaded_file($file_tmp, $destination)) {
                                $uploadedAt = date('Y-m-d H:i:s');
                                $sql = "INSERT INTO files (member_id, name, path, uploadedAt) VALUES (?, ?, ?, ?)";
                                $file_stmt = $conn->prepare($sql);
                                $file_stmt->bind_param("isss", $member_id, $file_name, $destination, $uploadedAt);

                                if ($file_stmt->execute()) {
                                    echo '<div class="alert alert-success" role="alert">Data saved to the database. File uploaded and saved to the database.</div>';
                                    echo '<script>window.location.href = "members.php";</script>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">Error: ' . $file_stmt->error . '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Error: Failed to move the uploaded file.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error: ' . $file_error . '</div>';
                        }
                    }
                } else {
                    echo '<div class="alert alert-success" role="alert">Data saved to the database.</div>';
                    echo '<script>window.location.href = "members.php";</script>';
                }
                echo '<div class="alert alert-success" role="alert">Data saved to the database. Pending approval by admin.</div>';
                echo '<script>window.location.href = "members.php";</script>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        mysqli_close($conn);
        ?>



        <!DOCTYPE html>
        <html>

        <body>
            <form name="item" method="POST" action="c_member.php" enctype="multipart/form-data">
                <div class="card card-block">
                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter First Name" required>
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Last Name" required>
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
                        <div class="form-group col-xs-3">
                            <label for="gender">Gender</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="" disabled selected>Select Sex</option>
                                <option value="1">MALE</option>
                                <option value="2">FEMALE</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-5">
                            <label for="contact">Contact Number</label>
                            <input type="text" class="form-control" name="contact" id="contact" placeholder="Enter Contact" required>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="status">Civil Status</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="" disabled selected>-Select Civil Status-</option>
                                <option value="1">SINGLE</option>
                                <option value="2">MARRIED</option>
                                <option value="3">WIDOWED</option>
                                <option value="4">DIVORCED</option>
                                <option value="5">SEPARATED</option>
                                <option value="6">REGISTERD PARTNERSHIP</option>
                            </select>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="religion">Religion</label>
                            <input type="text" class="form-control" name="religion" id="religion" placeholder="Enter Religion" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="address">Present Address</label>
                            <input type="text" class="form-control" name="address" id="address" placeholder="Enter Address" required>
                        </div>
                    </div>
                    <br>
                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="education">Educational Attainment</label>
                            <input type="text" class="form-control" name="education" id="education" placeholder="Enter Education Attained" required>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="work">Occupation</label>
                            <input type="text" class="form-control" name="work" id="work" placeholder="Enter Job" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="n_emergency">Person to Contact in case of Emergency</label>
                            <input type="text" class="form-control" name="n_emergency" id="n_emergency" placeholder="Enter Name" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="relation">Relation</label>
                            <input type="text" class="form-control" name="relation" id="relation" placeholder="Enter Relation" required>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="cn_emergency">Contact Number</label>
                            <input type="text" class="form-control" name="cn_emergency" id="cn_emergency" placeholder="Enter Contact" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-3">
                            <label for="tin">Tin Number</label>
                            <input type="text" class="form-control" name="tin" id="tin" placeholder="Enter Tin Number" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="dateApplied">Date Of Application</label>
                            <input type="date" class="form-control" name="dateApplied" id="dateApplied" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="photo"> Upload Files</label>
                    </div>
                    <div class="form-group">
                        <input type="file" name="photo[]" id="photo" multiple>
                    </div>

                    <div class="form-group">
                        <div id="selectedFiles"></div>
                    </div>




                    <div class="form-group" style="float: right;">
                        <a href="members.php" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-success" name="submit">Submit</button>

                    </div>
                </div>
            </form>
        </body>

        </html>

        <script>
            let selectedFiles = [];


            function handleFileSelect(event) {
                const files = event.target.files;


                for (let i = 0; i < files.length; i++) {
                    selectedFiles.push(files[i]);
                }


                updateSelectedFilesDisplay();
            }


            function removeSelectedFile(index) {
                selectedFiles.splice(index, 1);


                updateSelectedFilesDisplay();
            }


            function updateSelectedFilesDisplay() {
                const selectedFilesDiv = document.getElementById('selectedFiles');
                selectedFilesDiv.innerHTML = '';

                for (let i = 0; i < selectedFiles.length; i++) {
                    const file = selectedFiles[i];
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB

                    const fileDiv = document.createElement('div');
                    fileDiv.innerHTML = `
                <span>${fileName} (${fileSize} MB)</span>
                <button type="button" onclick="removeSelectedFile(${i})">Remove</button>
            `;
                    selectedFilesDiv.appendChild(fileDiv);
                }
            }


            function removeFile() {
                selectedFiles = [];

                const fileInput = document.getElementById('photo');
                fileInput.value = '';

                const selectedFilesDiv = document.getElementById('selectedFiles');
                selectedFilesDiv.innerHTML = '';
            }


            const fileInput = document.getElementById('photo');
            fileInput.addEventListener('change', handleFileSelect);
        </script>

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



        <script src="js/jquery.uploadfile.js"></script>
        <script src="js/jquery.uploadfile.min.js"></script>

        </body>

        </html>