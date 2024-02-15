<?php
include 'header.php';
include 'sidebar.php';
$allowedUserTypes = array(1, 4, 5);

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

        <body>
            <div class="card card-block">
                <div class="row form-group">
                    <div class="form-group col-xs-6">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control" value="<?php echo $userData['firstname']; ?>" readonly>
                    </div>
                    <div class="form-group col-xs-6">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control" value="<?php echo $userData['lastname']; ?>" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="birthday">Birthday:</label>
                        <input type="date" class="form-control" name="birthday" id="birthday" value="<?php echo $userData['birthday']; ?>" readonly>
                    </div>
                    <div class="form-group col-xs-1">
                        <label for="age">Age:</label>
                        <input type="text" class="form-control" name="age" id="age" value="<?php echo $userData['age']; ?>" required readonly>
                    </div>
                    <div class="form-group col-xs-3">
                        <label for="gender">Gender:</label>
                        <input type="text" class="form-control" name="gender" id="gender" value="<?php echo $userData['gender']; ?>" required readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-5">
                        <label for="contact">Contact Number:</label>
                        <input type="text" class="form-control" name="contact" id="contact" value="<?php echo $userData['contact']; ?>" required readonly>
                    </div>
                    <div class="form-group col-xs-3">
                        <label for="status">Civil Status:</label>
                        <input type="text" class="form-control" name="status" id="status" value="<?php echo $userData['status']; ?>" required readonly>
                    </div>
                    <div class="form-group col-xs-3">
                        <label for="religion">Religion:</label>
                        <input type="text" class="form-control" name="religion" id="religion" value="<?php echo $userData['religion']; ?>" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="presentAddress">Present Address:</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?php echo $userData['address']; ?>" required readonly>
                </div>
                <br>
                <div class="row form-group">
                    <div class="form-group col-xs-6">
                        <label for="education">Educational Attainment:</label>
                        <input type="text" class="form-control" name="education" id="education" value="<?php echo $userData['education']; ?>" required readonly>
                    </div>
                    <div class="form-group col-xs-6">
                        <label for="work">Occupation:</label>
                        <input type="text" class="form-control" name="work" id="work" value="<?php echo $userData['work']; ?>" required readonly>
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

                <div class="form-group">
                    <label for="n_emergency">Person to Contact in case of Emergency:</label>
                    <input type="text" class="form-control" value="<?php echo $userData['n_emergency']; ?>" readonly>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-6">
                        <label for="relation">Relation:</label>
                        <input type="text" class="form-control" value="<?php echo $userData['relation']; ?>" readonly>
                    </div>
                    <div class="form-group col-xs-6">
                        <label for="cn_emergency">Contact Number:</label>
                        <input type="text" class="form-control" value="<?php echo $userData['cn_emergency']; ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="dateApplied">Date Of Application:</label>
                        <input type="date" class="form-control" value="<?php echo $userData['dateApplied']; ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tin">Tin Number:</label>
                    <input type="text" class="form-control" value="<?php echo $userData['tin']; ?>" readonly>
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


                ?>



                <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='pending_mem.php'">
                        Back
                    </button>
                </div>
            </div>
    </div>
    </form>
    </body>

    </html>