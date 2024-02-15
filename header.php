<?php
require_once 'db_connect.php';
require_once 'core.php';

$email = $_SESSION['email'];

$query = mysqli_query($conn, "SELECT fname, userType FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($query);
$fname = $user['fname'];
$userType = $user['userType'];
?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>CAF-AGRI COOP INTEGRATED INFORMATION SYSTEM</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/logo.jpg">


    <link rel="stylesheet" type="text/css" href="fonts/fontawesome-free-6.5.1-web/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="fonts/fontawesome-free-6.5.1-web/css/solid.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
    <script src="js\jquery.min.js"></script>
    <link rel="stylesheet" href=" css\style.css">
    <link rel="stylesheet" href=" css\vendor.css">



</head>

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

<body>
    <div class="main-wrapper">
        <div class="app" id="app">
            <header style=" background-color: white;" class="header">
                <div class="header-block header-block -collapse hidden-lg-up">
                    <button class="collapse-btn" id="sidebar-collapse-btn">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
                <div class="header-block header-block-search hidden-sm-down">

                </div>
                <div class="header-block header-block-nav">
                    <ul class="nav-profile">



                        <li class="profile dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <div class="img" style="background-image: url('<?php echo $avatarURL; ?>')"></div>
                                <span class="name" style="color: black;">
                                    <?php echo $fname; ?>
                                </span>

                            </a>
                            <!-- Profile dropdown menu -->
                            <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                <?php if ($userType == 1) : ?>
                                    <a class="dropdown-item" href="index.php">
                                        <i class="fa fa-user icon"></i>
                                        Back to admin panel
                                    </a>
                                <?php endif; ?>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="logout.php">
                                    <i class="fa fa-power-off icon "></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </header>


            <div class="ref" id="ref">
                <div class="color-primary"></div>
                <div class="chart">
                    <div class="color-primary"></div>
                    <div class="color-secondary"></div>
                </div>
            </div>