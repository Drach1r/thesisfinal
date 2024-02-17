<?php


function getUserType()
{
    // Replace this logic with your actual authentication mechanism
    if (isset($_SESSION['userType'])) {
        return $_SESSION['userType'];
    } else {
        // Default to a guest role or handle it based on your application's requirements
        return 0;
    }
}

$userType = getUserType();

$allowedLinks = array(
    1 => array('dashboard', 'users', 'pending_mem'),
    2 => array('prod_dash', 'produce', 'production', 'inventory', 'supplier', 'product', 'category', 'customer', 'receiving', 'stocks', 'edit_produced.php'),
    3 => array('sales_dash', 'sales', 'inventory', 'c_sales'),
    4 => array('members', 'carabao', 'inventory', 'rawmaterials'),
    5 => array('dashboard', 'users', 'members', 'production', 'inventory', 'sales', 'payroll')
);
?>

<link rel="stylesheet" href=" css\sidebar.css">

<aside class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <div class="brand">
                <div class="">
                    <img src="assets/logo.jpg" alt="Your Logo">
                    <span class="l l1"></span>
                    <span class="l l2"></span>
                    <span class="l l3"></span>
                    <span class="l l4"></span>
                    <span class="l l5"></span>
                    <?php
                    // Assuming $userType contains the user type (1, 2, 3, or 4)
                    $userType = getUserType(); // Replace this with your actual logic to get the user type

                    // Echo the corresponding role name
                    if ($userType == 1) {
                        echo "Admin Module";
                    } elseif ($userType == 2) {
                        echo "Production Module";
                    } elseif ($userType == 3) {
                        echo "Sales Module";
                    } elseif ($userType == 4) {
                        echo "Bookkeeper Module";
                    } else {
                        // Handle other cases if needed
                        echo "Unknown Role";
                    }
                    ?>
                </div>
            </div>
        </div>


        <nav class="menu">
            <ul class="nav metismenu" id="sidebar-menu">

                <!-- Show all sidebar links if userType is 1 -->
                <?php if ($userType == 1) : ?>
                    <li class="">
                        <a href="index.php">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="">
                        <a href="users.php">
                            <i class="fa fa-user"></i> Manage Users
                        </a>
                    </li>
                    <li class="">
                        <a href="pending_mem.php">
                            <i class="fa fa-group"></i> Manage Members
                        </a>
                    </li>

                    <li class="">
                        <a href="optimization.php">
                            <i class="fa fa-group"></i> Optimization
                        </a>
                    </li>

                <?php endif; ?>

                <!-- Show only Production and Inventory sidebar links if userType is 2 -->
                <?php if ($userType == 2) : ?>
                    <li class="">
                        <a href="prod_dash.php">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="  ">
                        <a href="">
                            <i class="fa fa-archive"></i> Receiving Raw Products
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="produce.php">
                                    <i class="fa fa-archive"></i> Receiving Milk Produced
                                </a>
                            </li>
                            <li class="">
                                <a href="receiving.php">
                                    <i class="fa fa-truck"></i> Purchase Raw Materials
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="  ">
                        <a href="">
                            <i class="fa-solid fa-industry"></i> Production of Products
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="production.php">
                                    <i class="fa fa-archive"></i> Producing End Products
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="  ">
                        <a href="">
                            <i class="fa fa-clipboard"></i> Product Lists
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="rawmaterials.php">
                                    <i class="fa-solid fa-box"></i> Rawmaterials
                                </a>
                            </li>
                            <li class="">
                                <a href="product.php">
                                    <i class="fa-solid fa-box"></i> End Product
                                </a>
                            </li>


                        </ul>
                    </li>
                    <li class="  ">
                        <a href="">
                            <i class="fa fa-clipboard"></i> Inventory
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li lass="">
                                <a href="rawmats.php">
                                    <i class="fa fa-clipboard"></i> Raw Materials Inventory
                                </a>
                            </li>

                            <li class="">
                                <a href="stock.php">
                                    <i class="fa fa-clipboard"></i> End Products Inventory
                                </a>
                            </li>
                        </ul>
                    </li>



                <?php endif; ?>
                <?php if ($userType == 3) : ?>

                    <li class="">
                        <a href="sales_dash.php">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>

                    <li class="  ">
                        <a href="">
                            <i class="fa fa-shopping-cart"></i> Sales
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="sales.php">
                                    Sales list
                                </a>
                            </li>
                            <li>
                                <a href="c_sales.php">
                                    Add Sale
                                </a>
                            </li>
                            <!-- Add other sales-related links here -->
                        </ul>
                    </li>
                    <li>
                        <a href="stock.php">
                            <i class="fa fa-clipboard"></i> End Products Inventory
                        </a>
                    </li>
                    <li class="">
                        <a href="customer.php">
                            <i class="fa fa-users"></i> Customer List
                        </a>
                    </li>


                <?php endif; ?>
                <?php if ($userType == 4) : ?>
                    <li class="">
                        <a href="book_dash.php">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>

                    <li class=" ">
                        <a href="">
                            <i class="fa fa-users"></i> Members
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="members.php">
                                    Members list
                                </a>
                            </li>
                            <li class="">
                                <a href="carabao.php">
                                    Carabao list
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="  ">
                        <a href="">
                            <i class="fa fa-clipboard"></i> Inventory
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li lass="">
                                <a href="rawmats.php">
                                    <i class="fa fa-clipboard"></i> Raw Materials Inventory
                                </a>
                            </li>

                            <li class="">
                                <a href="stock.php">
                                    <i class="fa fa-clipboard"></i> End Products Inventory
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="payroll.php">
                            <i class="fa fa-clipboard"></i> Payroll
                        </a>
                    </li>
                    <li>
                        <a href="employee.php">
                            <i class="fa fa-clipboard"></i> Employee
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($userType == 5) : ?>
                    <li class="">
                        <a href="book_dash.php">
                            <i class="fa fa-home"></i> Bookkeeper Dashboard
                        </a>
                    </li>

                    <li class=" ">
                        <a href="">
                            <i class="fa fa-users"></i> Members
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="members.php">
                                    Members list
                                </a>
                            </li>
                            <li class="">
                                <a href="carabao.php">
                                    Carabao list
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="  ">
                        <a href="">
                            <i class="fa fa-clipboard"></i> Inventory
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li lass="">
                                <a href="rawmats.php">
                                    <i class="fa fa-clipboard"></i> Raw Materials Inventory
                                </a>
                            </li>

                            <li class="">
                                <a href="stock.php">
                                    <i class="fa fa-clipboard"></i> End Products Inventory
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="payroll.php">
                            <i class="fa fa-clipboard"></i> Payroll
                        </a>
                    </li>
                    <li>
                        <a href="employee.php">
                            <i class="fa fa-clipboard"></i> Employee
                        </a>
                    </li>
                    <li class="">
                        <a href="index.php">
                            <i class="fa fa-home"></i> Admin Dashboard
                        </a>
                    </li>
                    <li class="">
                        <a href="users.php">
                            <i class="fa fa-user"></i> Manage Users
                        </a>
                    </li>
                    <li class="">
                        <a href="pending_mem.php">
                            <i class="fa fa-group"></i> Manage Members
                        </a>
                    </li>

                    <li class="">
                        <a href="optimization.php">
                            <i class="fa fa-group"></i> Optimization
                        </a>
                    </li>
                    <li class="">
                        <a href="prod_dash.php">
                            <i class="fa fa-home"></i> Production Dashboard
                        </a>
                    </li>
                    <li class="  ">
                        <a href="">
                            <i class="fa fa-archive"></i> Receiving Raw Products
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="produce.php">
                                    <i class="fa fa-archive"></i> Receiving Milk Produced
                                </a>
                            </li>
                            <li class="">
                                <a href="receiving.php">
                                    <i class="fa fa-truck"></i> Purchase Raw Materials
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="  ">
                        <a href="">
                            <i class="fa-solid fa-industry"></i> Production of Products
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="production.php">
                                    <i class="fa fa-archive"></i> Producing End Products
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="  ">
                        <a href="">
                            <i class="fa fa-clipboard"></i> Product Lists
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="rawmaterials.php">
                                    <i class="fa-solid fa-box"></i> Rawmaterials
                                </a>
                            </li>
                            <li class="">
                                <a href="product.php">
                                    <i class="fa-solid fa-box"></i> End Product
                                </a>
                            </li>


                        </ul>
                    </li>
                    <li class="  ">
                        <a href="">
                            <i class="fa fa-clipboard"></i> Inventory
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li lass="">
                                <a href="rawmats.php">
                                    <i class="fa fa-clipboard"></i> Raw Materials Inventory
                                </a>
                            </li>

                            <li class="">
                                <a href="stock.php">
                                    <i class="fa fa-clipboard"></i> End Products Inventory
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="sales_dash.php">
                            <i class="fa fa-home"></i> Sales Dashboard
                        </a>
                    </li>

                    <li class="  ">
                        <a href="">
                            <i class="fa fa-shopping-cart"></i> Sales
                            <i class="fa arrow"></i>
                        </a>
                        <ul>
                            <li class="">
                                <a href="sales.php">
                                    Sales list
                                </a>
                            </li>
                            <li>
                                <a href="c_sales.php">
                                    Add Sale
                                </a>
                            </li>
                            <!-- Add other sales-related links here -->
                        </ul>
                    </li>
                    <li>
                        <a href="stock.php">
                            <i class="fa fa-clipboard"></i> End Products Inventory
                        </a>
                    </li>
                    <li class="">
                        <a href="customer.php">
                            <i class="fa fa-users"></i> Customer List
                        </a>
                    </li>

                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- First, include the jQuery library -->
<script src="js\jquery.min.js"></script>


<!-- Then, include the jQuery UI library -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Next, load other jQuery-dependent libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js" integrity="sha512-o36qZrjup13zLM13tqxvZTaXMXs+5i4TL5UWaDCsmbp5qUcijtdCFuW9a/3qnHGfWzFHBAln8ODjf7AnUNebVg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Add your CSS stylesheets -->

<link rel="stylesheet" href="css/vendor.css">


<!-- Your custom scripts -->
<script src="js/vendor.js"></script>
<script src="js/app.js"></script>