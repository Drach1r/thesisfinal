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
                        Add Customer
                    </h3>
                </div>
            </div>
        </div>

        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['c_name'];
            $contact = $_POST['c_phone'];
            $address = $_POST['c_address'];
            $email = $_POST['email'];
            $createdAt = date('Y-m-d H:i:s');  // Use current timestamp

            $stmt = $conn->prepare("INSERT INTO customers (Name, Phone, Address, Email, createdAt) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $contact, $address, $email, $createdAt);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success" role="alert">Customer created successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error creating customer: ' . $stmt->error . '</div>';
            }


            $stmt->close();
        }
        ?>


        <form name="item" method="POST" action="">
            <div class="card card-block">
                <div class="row form-group">
                    <div class="form-group col-xs-6">
                        <label for="fname">Name</label>
                        <input type="text" class="form-control" name="c_name" id="name" placeholder="Enter Full Name" required value="">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="contact">Contact</label>
                        <input type="text" class="form-control" name="c_phone" id="contact" placeholder="Enter contact" required value="">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Enter email" required value="">
                    </div>
                    <div class="form-group col-xs-4">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="c_address" id="address" placeholder="Enter Address" required value="">
                    </div>
                </div>

                <div style="float: right;" class=" form-group">
                    <a href="customer.php" class="btn btn-danger">Back</a>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
</article>