<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';



?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js">
<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Bills

                        <a href="c_bills.php" class="btn btn-primary btn-sm rounded-s">
                            Pay
                        </a>
                    </h3>
                </div>
            </div>
        </div>


        <section class="section">
            <div class="row">
                <?php

                if (isset($_GET['delete'])) {

                    $id = $_GET['delete'];


                    $sql = "DELETE FROM bill_records WHERE id = $id";


                    if (mysqli_query($conn, $sql)) {

                        echo "<div class='alert alert-success' role='alert'>Record with ID $id has been deleted successfully</div>";
                    } else {

                        echo "<div class='alert alert-danger' role='alert'>Error deleting record: " . mysqli_error($conn) . "</div>";
                    }
                } ?>
                <div class="card col-lg-12">
                    <br>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-title-body">
                                <section class="example">

                                    <script>
                                        $(document).ready(function() {
                                            var table = $('#recordstable').DataTable({
                                                'pageLength': 1,
                                                'scrollY': '40vh',
                                                columnDefs: [{
                                                        width: '10%',
                                                        targets: 1
                                                    },
                                                    {
                                                        width: '10%',
                                                        targets: 3
                                                    },
                                                ]
                                            });

                                            $('.nav-link').click(function() {
                                                table.columns.adjust().draw();

                                            });


                                        });
                                    </script>
                                    <style type="text/css">
                                        table tbody tr:hover {
                                            cursor: pointer;
                                        }

                                        .normalTr:hover {
                                            cursor: default;
                                        }
                                    </style>
                                    <table class="table table-bordered table-hover w-100" id="recordstable">
                                        <thead class="table-dark">
                                            <tr>

                                                <th class='text-center'>Date</th>
                                                <th class='text-center'>Bills</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT bill_records.id, bill_records.date, bills.bill_name, bill_records.amount 
                                    FROM bill_records 
                                    INNER JOIN bills ON bill_records.bill_id = bills.bill_id 
                                    ORDER BY bill_records.id DESC";


                                            $result = mysqli_query($conn, $sql);

                                            // Check if there are rows in the result
                                            if (mysqli_num_rows($result) > 0) {
                                                // Loop through each row and display data
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td class='text-center'>" . $row['date'] . "</td>";
                                                    echo "<td class='text-center'>" . $row['bill_name'] . "</td>"; // Display bill_name instead of bill_id
                                                    echo "<td class='text-center'>" . $row['amount'] . "</td>";
                                                    echo "<td class='text-center'>";
                                                    echo "<a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this data?\")'' class='btn btn-danger btn-sm'>Delete</a>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                // If no rows found, display message
                                                echo "<tr><td colspan='5'>No bills found</td></tr>";
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</article>

</body>

</html>