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
                        Members

                    </h3>

                </div>


            </div>
        </div>
        <?php
        include 'db_connect.php';

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

            // Prepare the update statement
            $stmt = $conn->prepare("UPDATE member SET firstname = ?, lastname = ?, age = ?, status = ?, gender = ?, religion = ?, birthday = ?, work = ?, address = ?, education = ?, contact = ?, tin = ?, dateApplied = ?,  n_emergency = ?, relation = ?, cn_emergency = ? WHERE id = ?");

            // Bind the parameters to the statement
            $stmt->bind_param("ssssssssssssssssi", $firstname, $lastname, $age, $status, $gender, $religion, $birthday, $work, $address, $education, $contact, $tin, $dateApplied, $n_emergency, $relation, $cn_emergency, $id);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success" role="alert">Data updated successfully.</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">Error updating data: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];

            // Check if the file is uploaded successfully
            if (move_uploaded_file($fileTmpName, 'assets/files/' . $fileName)) {
                // Insert file information into the database
                $insertFileStmt = $conn->prepare("INSERT INTO files (member_id, name) VALUES (?, ?)");
                $insertFileStmt->bind_param("is", $id, $fileName);
                $insertFileStmt->execute();
                $insertFileStmt->close();

                echo '<div class="alert alert-success" role="alert">File uploaded successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error uploading file.</div>';
            }
        }


        $id = $_GET['id']; // Assuming you're passing the member ID via GET parameter
        $stmt = $conn->prepare("SELECT member.id, member.firstname, member.lastname, member.birthday, member.age, member.gender, member.contact, member.status, member.religion, member.address, member.education, member.work, carabaos.name AS carabao_name, member.n_emergency, member.relation, member.cn_emergency, member.dateApplied,  member.tin
          FROM member 
          LEFT JOIN carabaos ON member.id = carabaos.member_id
          WHERE member.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        } else {
            // Handle the case when the member data is not found
            echo "Member not found.";
            exit;
        }

        $stmt->close();
        ?>

        <!doctype html>
        <html>

        <body>
            <form name="editUser" method="POST" action="" enctype="multipart/form-data">
                <div class="card card-block">
                    <div class="form-group row">
                        <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="firstname">First Name:</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $userData['firstname']; ?>" required>
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="lastname">Last Name:</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $userData['lastname']; ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="birthday">Birthday:</label>
                            <input type="date" class="form-control" name="birthday" id="birthday" value="<?php echo $userData['birthday']; ?>" required>
                        </div>
                        <div class="form-group col-xs-1">
                            <label for="age">Age:</label>
                            <input type="text" class="form-control" name="age" id="age" value="<?php echo $userData['age']; ?>" required readonly>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="gender">Gender:</label>
                            <input type="text" class="form-control" name="gender" id="gender" value="<?php echo $userData['gender']; ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-5">
                            <label for="contact">Contact Number:</label>
                            <input type="text" class="form-control" name="contact" id="contact" value="<?php echo $userData['contact']; ?>" required>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="status">Civil Status:</label>
                            <input type="text" class="form-control" name="status" id="status" value="<?php echo $userData['status']; ?>" required>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="religion">Religion:</label>
                            <input type="text" class="form-control" name="religion" id="religion" value="<?php echo $userData['religion']; ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="presentAddress">Present Address:</label>
                            <input type="text" class="form-control" name="address" id="address" value="<?php echo $userData['address']; ?>" required>
                        </div>
                    </div>
                    <br>
                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="education">Educational Attainment:</label>
                            <input type="text" class="form-control" name="education" id="education" value="<?php echo $userData['education']; ?>" required>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="work">Occupation:</label>
                            <input type="text" class="form-control" name="work" id="work" value="<?php echo $userData['work']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="carabaos"></label>
                        <?php
                        // Check if the member has registered carabaos
                        $memberId = $userData['id'];
                        $stmt_carabaos = $conn->prepare("SELECT name FROM carabaos WHERE member_id = ?");
                        $stmt_carabaos->bind_param("i", $memberId);
                        $stmt_carabaos->execute();
                        $result_carabaos = $stmt_carabaos->get_result();

                        if ($result_carabaos->num_rows > 0) {
                            echo '<p><strong>Registered Carabaos:</strong></p>';
                            echo '<ul>';
                            while ($row_carabao = $result_carabaos->fetch_assoc()) {
                                echo '<li>' . $row_carabao['name'] . '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>No registered carabaos for this member.</p>';
                        }
                        $stmt_carabaos->close();
                        ?>
                    </div>


                    <div class="row form-group">
                        <div class="form-group col-xs-6">
                            <label for="n_emergency">Person to Contact in case of Emergency:</label>
                            <input type="text" class="form-control" name="n_emergency" id="n_emergency" value="<?php echo $userData['n_emergency']; ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="relation">Relation:</label>
                            <input type="text" class="form-control" name="relation" id="relation" value="<?php echo $userData['relation']; ?>" required>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="cn_emergency">Contact Number:</label>
                            <input type="text" class="form-control" name="cn_emergency" id="cn_emergency" value="<?php echo $userData['cn_emergency']; ?>" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="form-group col-xs-3">
                            <label for="tin">Tin Number:</label>
                            <input type="text" class="form-control" name="tin" id="tin" placeholder="Enter Tin Number" value="<?php echo $userData['tin']; ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="dateApplied">Date Of Application:</label>
                            <input type="date" class="form-control" name="dateApplied" id="dateApplied" value="<?php echo $userData['dateApplied']; ?>" required>
                        </div>

                    </div>

                    <div class="card card-block">
                        <h4>Files:</h4>
                        <?php
                        // Retrieve files associated with the member
                        $selectFilesStmt = $conn->prepare("SELECT * FROM files WHERE member_id = ?");
                        $selectFilesStmt->bind_param("i", $id);
                        $selectFilesStmt->execute();
                        $filesResult = $selectFilesStmt->get_result();

                        if ($filesResult->num_rows > 0) {
                            while ($fileData = $filesResult->fetch_assoc()) {
                                $fileId = $fileData['id'];
                                $fileName = $fileData['name'];

                                echo '<div>';
                                echo '<a href="assets/files/' . $fileName . '" target="_blank">' . $fileName . '</a>';
                                echo '<a href="?id=' . $id . '&delete_file=' . $fileId . '" class="btn btn-sm btn-danger ml-2">Delete</a>';
                                echo '</div>';
                            }
                        } else {
                            echo 'No files found.';
                        }

                        $selectFilesStmt->close();

                        // Handle file deletion
                        if (isset($_GET['delete_file'])) {
                            $deleteFileId = $_GET['delete_file'];

                            // Retrieve file name from the database
                            $selectFileStmt = $conn->prepare("SELECT name FROM files WHERE id = ?");
                            $selectFileStmt->bind_param("i", $deleteFileId);
                            $selectFileStmt->execute();
                            $fileResult = $selectFileStmt->get_result();

                            if ($fileResult->num_rows > 0) {
                                $fileData = $fileResult->fetch_assoc();
                                $fileName = $fileData['name'];
                                $filePath = 'assets/files/' . $fileName;

                                // Delete the file from the server's file system
                                if (file_exists($filePath)) {
                                    unlink($filePath);
                                }

                                // Delete the file record from the database
                                $deleteFileStmt = $conn->prepare("DELETE FROM files WHERE id = ?");
                                $deleteFileStmt->bind_param("i", $deleteFileId);
                                $deleteFileStmt->execute();

                                echo '<div class="alert alert-success" role="alert">File deleted successfully.</div>';

                                $deleteFileStmt->close();
                            } else {
                                echo '<div class="alert alert-danger" role="alert">File not found.</div>';
                            }

                            $selectFileStmt->close();
                        }
                        ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">
                                <label for="file">Upload File</label>
                                <input type="file" class="form-control-file" name="file" id="file">
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                    </div>

                    <div class="row form-group" style="float: right;">
                        <a href="members.php" class="btn btn-danger">
                            Back
                        </a>
                        <button type="submit" class="btn btn-success">Update</button>

                    </div>
                </div>
            </form>
        </body>

        </html>