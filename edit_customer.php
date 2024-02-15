<?php
include 'header.php';
include 'sidebar.php';
?>
            
   
                  
    <article class="content items-list-page">
      <div class="title-search-block">

	<div class="title-block">
		<div class="row">

			<div class="col-sm-6">
				<h3 class="title">
					Edit Supplier
					
				</h3>
				
			</div>


		</div>
    </div>
  



<?php


include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the edited data from the form
    $id = $_POST['id'];
    $name = sanitize($_POST['c_name']);
	$contact = sanitize($_POST['c_phone']);
    $address = sanitize($_POST['c_address']);
	$updatedAt = sanitize($_POST['updatedAt']);

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE customer SET c_name = ?, c_phone = ?, c_address = ?, updatedAt = ? WHERE id = ?");
    
    // Bind the parameters to the statement
    $stmt->bind_param("ssssi", $name, $contact, $address, $updatedAt, $id);

    // Execute the update statement
    if ($stmt->execute()) { 
        echo '<div class="alert alert-success" role="alert">Data updated successfully.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error updating data: ' . $stmt->error . '</div>';
    }

    // Close the statement
    $stmt->close();
}

// Check if an 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the record from the database based on the provided 'id'
    $stmt = $conn->prepare("SELECT * FROM customer WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the record exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
				 $name = $row['c_name'];
				  $contact = $row['c_phone'];
				  $address = $row['c_address'];
				  $updatedAt = $row['updatedAt'];
    } else {
        echo '<div class="alert alert-danger" role="alert">Record not found.</div>';
        exit;
    }

    // Close the statement
    $stmt->close();
} else {
    echo '<div class="alert alert-danger" role="alert">Invalid request.</div>';
    exit;
}

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

?>

<!doctype html>
<html>
<body>
    <form name="item" method="POST" action="">
        <div class="card card-block">
            <div class="form-group row">
                <div class="form-group">
                    <label for="fname">Name</label>
                    <input type="text" class="form-control" name="c_name" id="name" placeholder="Enter Full Name" required value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="contact" class="form-control" name="c_phone" id="contact" placeholder="Enter contact" required value="<?php echo $contact; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="c_address" id="address" placeholder="Enter Address" required value="<?php echo $address; ?>">
                </div>
                <div class="form-group">
    <label for="updatedAt">Updated Date and Time</label>
    <input type="datetime-local" class="form-control" name="updatedAt" id="updatedAt" placeholder="Updated Date and Time" required value="<?php echo $updatedAt; ?>">
</div>

                <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="customer.php" class="btn btn-primary">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </form>

	
</body>
</html>
