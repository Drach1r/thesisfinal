<?php
session_start();
include 'db_connect.php';

$error = '';

if (isset($_POST['email']) && isset($_POST['password'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
	$rows = mysqli_fetch_assoc($query);
	$num = mysqli_num_rows($query);

	if ($num == 1) {
		// Set the user's session variables
		$_SESSION['email'] = $rows['email'];
		$_SESSION['user'] = $rows['name'];
		$_SESSION['userType'] = $rows['userType']; // Assuming you have 'userType' stored in your database

		// Check the user type and redirect accordingly
		if ($rows['userType'] == 1) {
			header("Location: index.php");
			exit();
		} elseif ($rows['userType'] == 2) {
			header("Location: prod_dash.php");
		} elseif ($rows['userType'] == 3) {
			header("Location: sales_dash.php");
		} elseif ($rows['userType'] == 4) {
			header("Location: book_dash.php");
		} elseif ($rows['userType'] == 5) {
			header("Location: index.php");
			exit();
		}
	} else {
		$error = "Email or Password is invalid";
		$_SESSION['error'] = $error; // Store the error message in the session
	}

	mysqli_close($conn);
}
?>

<script type="text/javascript">
	function preventBack() {
		window.history.forward()
	};
	setTimeout("preventBack()",
		0);
	window.onunload = function() {
		null;
	}
</script>

<!doctype html>
<html class="no-js" lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>CAF-AGRI Integrated System</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="assets/logo.jpg">


	<link rel="stylesheet" href="css/vendor.css">


	<script>
		var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) : {};
		var themeName = themeSettings.themeName || '';

		if (themeName) {
			document.write('<link rel="stylesheet" id="theme-style" href="css/app-' + themeName + '.css">');
		} else {
			document.write('<link rel="stylesheet" id="theme-style" href="css/app.css">');
		}
	</script>


</head>
<style>
	body {
		background-color: green;
	}
</style>

<body>

	<div class="auth">
		<div class="auth-container">
			<div class="card">
				<header class="auth-header">
					<div class="brand">
						<img style="width: 100px; height: auto;" src="assets/logo.jpg" alt="Your Logo">
					</div>

				</header>
				<div class="auth-content">
					<?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])) : ?>
						<div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
						<?php unset($_SESSION['error']);
						?>
					<?php endif; ?>
					<p class="text-center">LOGIN TO CONTINUE</p>
					<form id="login-form" action="" method="POST" novalidate="">
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control underlined" name="email" id="email" placeholder="Your email address" required>
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" class="form-control underlined" name="password" id="password" placeholder="Your password" required>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-block btn-primary">Login</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
	<!-- Reference block for JS -->
	<div class="ref" id="ref">
		<div class="color-primary"></div>
		<div class="chart">
			<div class="color-primary"></div>
			<div class="color-secondary"></div>
		</div>
	</div>
	<script src="js/vendor.js"></script>
	<script src="js/app.js"></script>


</body>

</html>