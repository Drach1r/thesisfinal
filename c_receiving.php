<?php
include 'header.php';
include 'sidebar.php';

$successMessage = ''; // Initialize success message



?>

<article class="content item-editor-page">

	<div class="title-block">
		<div class="row">
			<div class="col-sm-6">
				<h3 class="title">
					Purchase Raw Materials

				</h3>
			</div>
		</div>
	</div>

	<?php

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$rawMaterialID = $_POST['raw'];
		$purchaser = $_POST['purchaser'];
		$purchaseDate = isset($_POST['PurchaseDate']) ? $_POST['PurchaseDate'] : null;
		$quantity = isset($_POST['Quantity']) ? $_POST['Quantity'] : null;
		$unit = isset($_POST['unit']) ? $_POST['unit'] : null;
		$p_amount = isset($_POST['p_amount']) ? $_POST['p_amount'] : null;

		// Check if unit is set before proceeding with the SQL query
		if ($unit !== null) {
			$stmt = $conn->prepare("INSERT INTO purchases (RawMaterialID, buyer, PurchaseDate, qty_purchased, unit, p_amount, status) VALUES (?, ?, NOW(), ?, ?, ?, 0)");
			$stmt->bind_param("isdsi", $rawMaterialID, $purchaser, $quantity, $unit, $p_amount);

			if ($stmt->execute()) {
				// Display a JavaScript alert with a success message
				echo '<div class="alert alert-success" role="alert">Data Added successfully.</div>';
			} else {
				// Display a JavaScript alert with an error message
				echo '<div class="alert alert-danger" role="alert">Error Adding data: ' . $stmt->error . '</div>';
			}

			$stmt->close();
		} else {
			// Display an error message if unit is not set
			echo '<div class="alert alert-danger" role="alert">Error: Unit is required.</div>';
		}
	}

	?>

	<form name="item" method="POST" action="">
		<div class="card card-block">
			<div class="form-group row">

				<div class="form-group col-xs-6">

					<label for="purchaser">Purchaser</label>
					<select class="form-control" name="purchaser" id="" required>
						<option value="" disabled selected>-Select Employee-</option>
						<?php
						$position = 'processor'; // Set the desired position
						$employeeQuery = $conn->query("SELECT * FROM employee WHERE position = '$position' ORDER BY name");

						while ($row = $employeeQuery->fetch_assoc()) {
							echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="form-group col-xs-6">
					<label for="rawMaterial">Raw Material</label>
					<select class="form-control" name="raw" id="rawMaterial" required>
						<option value="" disabled selected>-Select Raw Material-</option>
						<?php
						// Retrieve raw materials from the rawmaterials table
						$rawmaterialQuery = $conn->query("SELECT * FROM rawmaterials WHERE RawMaterialID != 205 ORDER BY name ");
						while ($rawMaterialRow = $rawmaterialQuery->fetch_assoc()) {
							echo '<option value="' . $rawMaterialRow['RawMaterialID'] . '">' . $rawMaterialRow['Name'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>

			<div class="row form-group">
				<div class="form-group col-xs-4">
					<label for="Quantity">Qty</label>
					<input type="number" class="form-control" name="Quantity" id="Quantity" placeholder="Enter Quantity" required>
				</div>
				<div class="form-group col-xs-4">
					<label for="unit">Unit</label>
					<select class="form-control" name="unit" required>

						<option value="">---Select Unit---</option>
						<option value="Kg">Kilograms (kg)</option>
						<option value="G">Gram (g)</option>
						<option value="Mg">Milligram (mg)</option>
						<option value="L">Liter (l)</option>
						<option value="Ml">Millilitre (ml)</option>
					</select>
				</div>
			</div>
			<div class="row form-group">
				<div class="form-group col-xs-4">
					<label for="p_amount">Purchase amount</label>
					<input type="number" class="form-control" name="p_amount" id="p_amount" placeholder="Purchase Total" required>
				</div>
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-success">Add to List</button>
				<a href="receiving.php" class="btn btn-danger">Back</a>
			</div>

		</div>
	</form>
</article>

</html>
</body>

</html>