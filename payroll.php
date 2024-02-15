<!-- print_receipt.php -->
<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';
?>


<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Payout Slips
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <?php


    // Get distinct member IDs
    $queryMembers = "SELECT DISTINCT p.member_id, m.firstname, m.lastname FROM produced p
                    INNER JOIN member m ON p.member_id = m.id";
    $resultMembers = mysqli_query($conn, $queryMembers);

    // Create a weekly payout table
    echo '<section class="section">';
    echo '<div class="row">';
    echo '<div class="card col-lg-12">';
    echo '<div class="card-body">';
    echo '<div class="card-body">';
    echo '<div class="card-title-body"></div>';
    echo '<section class="example">';
    echo '<table class="table table-bordered col-md-12">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>#</th>';
    echo '<th>Member Name</th>';
    echo '<th>Carabao Name</th>';
    echo '<th>Weekly Transactions Count</th>';
    echo '<th>Weekly Actual Amount</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop through each member
    $count = 1;
    while ($rowMember = mysqli_fetch_assoc($resultMembers)) {
        $memberID = $rowMember['member_id'];

        // Get weekly transaction count and actual amount for each member
        $queryWeekly = "SELECT COUNT(*) AS weekly_count, SUM(p.actual) AS weekly_actual, c.name AS carabao_name FROM produced p
                        INNER JOIN carabaos c ON p.carabao_id = c.id
                        WHERE p.member_id = '$memberID' AND WEEK(p.date) = WEEK(CURDATE())";
        $resultWeekly = mysqli_query($conn, $queryWeekly);
        $rowWeekly = mysqli_fetch_assoc($resultWeekly);

        echo '<tr>';
        echo '<td>' . $count . '</td>';
        echo '<td>' . $rowMember['firstname'] . ' ' . $rowMember['lastname'] . '</td>';
        echo '<td>' . $rowWeekly['carabao_name'] . '</td>';
        echo '<td>' . $rowWeekly['weekly_count'] . '</td>';
        echo '<td>' . $rowWeekly['weekly_actual'] . '</td>';
        echo '<td><a href="transhis.php?member_id=' . $memberID . '">Transactions</a></td>';

        echo '</tr>';

        $count++;
    }

    echo '</tbody>';
    echo '</table>';
    echo '</section>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</section>';
    ?>


</article>