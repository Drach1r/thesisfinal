<?php
include 'header.php';
include 'sidebar.php';

$allowedUserTypes = array(4, 5);
checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

include 'db_connect.php';

function updateCarabaoData($conn, $carabao_id)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $member_id = $_POST["member_id"];
        $name = $_POST["name"];
        $age = $_POST["age"];
        $gender = $_POST["gender"];

        // Prepare the update statement
        $updateStmt = $conn->prepare("UPDATE carabaos SET member_id=?, name=?, age=?, gender=? WHERE id=?");
        $updateStmt->bind_param("ssssi", $member_id, $name, $age, $gender, $carabao_id);

        // Execute the update statement
        if ($updateStmt->execute()) {
            return true; // Data updated successfully
        } else {
            echo '<div class="alert alert-danger" role="alert">Error updating data: ' . $updateStmt->error . '</div>';
        }

        // Close the statement
        $updateStmt->close();
    }

    return false; // Form not submitted or data update failed
}

if (isset($_GET['id'])) {
    $carabao_id = $_GET['id'];
    $updateSuccess = updateCarabaoData($conn, $carabao_id);

    // Fetch carabao data for pre-filling the form
    $carabaoDataQuery = "SELECT * FROM carabaos WHERE id = $carabao_id";
    $carabaoDataResult = mysqli_query($conn, $carabaoDataQuery);
    $carabaoData = mysqli_fetch_assoc($carabaoDataResult);
} else {
    echo '<div class="alert alert-danger" role="alert">Carabao ID not provided.</div>';
    exit;
}
?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Edit Carabao Info

                    </h3>
                </div>
            </div>
        </div>

        <html>

        <body>
            <?php
            // Display success message if the form was submitted and data was updated successfully
            if (isset($updateSuccess) && $updateSuccess) {
                echo '<div class="alert alert-success" role="alert">Data updated successfully.</div>';
            }
            ?>
            <form name="editCarabao" method="POST" action="">
                <div class="card card-block">
                    <div class="card card-block">
                        <div class="row form-group">
                            <div class="form-group col-xs-4">
                                <label for="member_id">Cooperator</label>
                                <select class="form-control" name="member_id" id="member_id" required>
                                    <option value=""> --- SELECT ---</option>
                                    <?php
                                    $members = $conn->query("SELECT * FROM member ORDER BY id");
                                    while ($row = $members->fetch_assoc()) {
                                        $selected = ($row['id'] == $carabaoData['member_id']) ? 'selected' : '';
                                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['lastname'] . ', ' . $row['firstname'] . ' - ' . $row['carabao'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="form-group col-xs-4">
                                <label for="name">Carabao Tag</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Tag" required value="<?php echo $carabaoData['name']; ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="form-group col-xs-1">
                                <label for="age">Age</label>
                                <input type="text" class="form-control" name="age" id="age" placeholder="Enter Age" required value="<?php echo $carabaoData['age']; ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="form-group col-xs-3">
                                <label for="gender">Gender</label>
                                <select class="form-control" name="gender" id="gender" required value="<?php echo $carabaoData['gender']; ?>">>
                                    <option value="" disabled selected>-Select Gender-</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group" style="float:right;">
                            <a href="carabao.php" class="btn btn-danger">Back</a>
                            <button type="submit" class="btn btn-success">Update</button>

                        </div>
                    </div>
            </form>
        </body>

        </html>