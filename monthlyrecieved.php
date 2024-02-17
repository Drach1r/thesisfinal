<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';



function fetchMilkReceivedData($conn, $selectedDate)
{
    // Get the first day and last day of the selected month
    $firstDay = date('Y-m-01', strtotime($selectedDate));
    $lastDay = date('Y-m-t', strtotime($selectedDate));

    // Prepare the SQL query to fetch data for the selected month
    $query = "SELECT member_id, carabao_id, date, actual
              FROM produced
              WHERE date BETWEEN '$firstDay' AND '$lastDay'
              ORDER BY date";

    // Execute the query and fetch the result
    $result = mysqli_query($conn, $query);

    // Create an associative array to store milk received data for each day
    $milkData = array();

    // Loop through the result and store the data in the associative array
    while ($row = mysqli_fetch_assoc($result)) {
        $date = $row['date'];
        $memberId = $row['member_id'];
        $carabaoId = $row['carabao_id'];
        $milkQuantity = $row['actual'];

        // Store the data in the associative array
        $milkData[$date][$memberId][$carabaoId] = $milkQuantity;
    }

    return $milkData;
}

// Check if the form was submitted
if (isset($_POST['selectedDate'])) {
    // Get the selected date from the form
    $selectedDate = $_POST['selectedDate'];

    // Fetch milk received data for the selected month
    $milkData = fetchMilkReceivedData($conn, $selectedDate);
}

// Function to get the name of a cooperator based on their ID
function getMemberName($conn, $memberId)
{
    $query = "SELECT firstname, lastname FROM member WHERE id = $memberId";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    // Change the format to "lastname, firstname"
    return $row['lastname'] . ', ' . $row['firstname'];
}

// Get all the cooperators' names and their IDs from the database
function getCooperators($conn)
{
    $query = "SELECT id, firstname, lastname FROM member ORDER BY lastname";
    $result = mysqli_query($conn, $query);
    $cooperators = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $cooperators[$row['id']] = $row['lastname'] . ', ' . $row['firstname'];
    }
    return $cooperators;
}

// Fetch all cooperators
$cooperators = getCooperators($conn);


function getCarabaoName($conn, $carabaoId)
{
    $query = "SELECT name FROM carabaos WHERE id = $carabaoId";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['name'];
}
?>


<article class="content items-list-page">
    <div class="title-search-block">

        <div class="title-block">

            <div class="row">
                <div class="col-sm-6">

                    <h3 class="title">
                        Monthly Milk Recieved List

                    </h3>

                </div>
            </div>

        </div>
        <!-- HTML form to select the month -->
        <form method="post">
            <label for="selectedDate">Select Month:</label>
            <input type="month" id="selectedDate" name="selectedDate" required>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <?php if (isset($milkData) && !empty($milkData)) { ?>
            <a href="#" class="btn btn-primary" id="exportExcel">Export to Excel</a>
        <?php } ?>

        <table class="table table-bordered col-md-12" id="milkDataTable">
            <thead>
                <tr>
                    <?php if (isset($selectedDate)) { ?>
                        <th colspan="100%">Month: <?php echo date('F Y', strtotime($selectedDate)); ?></th>
                    <?php } else { ?>
                        <th colspan="100%">Month: <?php echo date('F Y'); ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <th>Cooperator Name</th>
                    <th>Carabao Name</th>
                    <?php
                    if (isset($milkData) && !empty($milkData)) {
                        $firstDayOfMonth = date('Y-m-01', strtotime($selectedDate));
                        $lastDayOfMonth = date('Y-m-t', strtotime($selectedDate));
                        $currentDate = $firstDayOfMonth;
                        while ($currentDate <= $lastDayOfMonth) {
                            echo '<th>' . date('d', strtotime($currentDate)) . '</th>';
                            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                        }
                    }
                    ?>
                </tr>
                <tr></tr>
            </thead>
            <tbody>
                <?php
                if (isset($milkData) && !empty($milkData)) {
                    // Initialize variables
                    $combinedData = array();
                    $totalProduction = array();

                    foreach ($milkData as $date => $cooperators) {
                        foreach ($cooperators as $memberId => $carabaos) {
                            foreach ($carabaos as $carabaoId => $milkQuantity) {
                                // Create a unique key for each carabao
                                $carabaoKey = $memberId . '_' . $carabaoId;

                                // Check if this carabao's data is already in the combinedData array
                                if (isset($combinedData[$carabaoKey])) {
                                    // If it is, update the milk quantity for the current date
                                    $combinedData[$carabaoKey]['milkQuantity'][$date] = $milkQuantity;
                                } else {
                                    // If it's not, create a new entry for the carabao
                                    $combinedData[$carabaoKey] = array(
                                        'memberId' => $memberId,
                                        'carabaoId' => $carabaoId,
                                        'cooperatorName' => getMemberName($conn, $memberId),
                                        'carabaoName' => getCarabaoName($conn, $carabaoId),
                                        'milkQuantity' => array($date => $milkQuantity),
                                    );
                                }

                                // Calculate the total production for each day
                                if (isset($totalProduction[$date])) {
                                    $totalProduction[$date] += $milkQuantity;
                                } else {
                                    $totalProduction[$date] = $milkQuantity;
                                }
                            }
                        }
                    }

                    usort($combinedData, function ($a, $b) {
                        return strcmp($a['cooperatorName'], $b['cooperatorName']);
                    });

                    // Display the combined data in the table
                    foreach ($combinedData as $data) {
                        echo '<tr>';
                        echo '<td>' . $data['cooperatorName'] . '</td>';
                        echo '<td>' . ($data['carabaoName'] ?? '') . '</td>';

                        // Display milk quantity for each day of the month
                        $currentDate = $firstDayOfMonth;
                        while ($currentDate <= $lastDayOfMonth) {
                            $dateKey = date('Y-m-d', strtotime($currentDate));
                            $milkQuantity = isset($data['milkQuantity'][$dateKey]) ? $data['milkQuantity'][$dateKey] : '';

                            // Display the milk quantity for the current date
                            echo '<td>' . $milkQuantity . '</td>';

                            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                        }

                        echo '</tr>';
                    }
                    // Calculate the monthly production total
                    $monthlyTotal = array_sum($totalProduction);

                    // Display the separator row
                    echo '<tr>';
                    echo '<td colspan="' . (count($totalProduction) + 2) . '"></td>'; // Empty cell spanning all columns
                    echo '</tr>';

                    // Display the total production row
                    echo '<tr>';
                    echo '<td><strong>Daily Production Total</strong></td>';
                    echo '<td></td>'; // Empty cell for Carabao Name

                    // Display the total production for each day of the month
                    $currentDate = $firstDayOfMonth;

                    while ($currentDate <= $lastDayOfMonth) {
                        $dateKey = date('Y-m-d', strtotime($currentDate));
                        $totalProductionPerDay = isset($totalProduction[$dateKey]) ? $totalProduction[$dateKey] : '';

                        // Display the total production for the current date
                        echo '<td><strong>' . $totalProductionPerDay . '</strong></td>';

                        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                    }

                    echo '</tr>';

                    // Display the monthly production total row
                    echo '<tr>';
                    echo '<td><strong>Monthly Production Total</strong></td>';
                    echo '<td></td>'; // Empty cell for Carabao Name
                    echo '<td colspan="' . count($totalProduction) . '"><strong>' . $monthlyTotal . '</strong></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>

        </table>

        <script>
            var milkData = <?php echo json_encode($milkData); ?>;
        </script>

        <div class="form-group">

            <a href="payroll.php" class="btn btn-primary">Back</a>
        </div>
        <!-- Include the exceljs library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

        <!-- Include xlsx-style library -->
        <script src="https://cdn.jsdelivr.net/npm/xlsx-style@0.8.13/dist/xlsx.full.min.js"></script>

        <script>
            document.getElementById("exportExcel").addEventListener("click", function() {
                // Create a new Workbook using ExcelJS
                var wb = new ExcelJS.Workbook();
                var ws = wb.addWorksheet("MilkData");

                // Get the HTML table element
                var table = document.getElementById("milkDataTable");

                // Loop through each row and column of the table and populate the worksheet
                for (var i = 0; i < table.rows.length; i++) {
                    var row = table.rows[i];
                    for (var j = 0; j < row.cells.length; j++) {
                        var cell = row.cells[j];
                        var cellValue = cell.innerText;
                        ws.getCell(i + 1, j + 1).value = cellValue; // Set cell value in the worksheet
                    }
                }

                // Auto-fit column widths based on the content
                ws.columns.forEach(column => {
                    var maxLength = 0;
                    column.eachCell({
                        includeEmpty: true
                    }, cell => {
                        const cellValue = cell.text || '';
                        const cellLength = cellValue.toString().length;
                        if (cellLength > maxLength) {
                            maxLength = cellLength;
                        }
                    });
                    column.width = maxLength + 2; // Add some buffer space to avoid cutting off content
                });

                // Write the workbook data to a buffer
                wb.xlsx.writeBuffer().then(function(buffer) {
                    // Create a Blob from the buffer
                    var blob = new Blob([buffer], {
                        type: "application/octet-stream"
                    });

                    // Create a download link and trigger the download
                    var link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = "Monthly MilkData .xlsx";


                    // Append the link to the document (important for some browsers)
                    document.body.appendChild(link);

                    // Trigger the click event on the link
                    link.click();

                    // Remove the link from the document (optional, but recommended)
                    document.body.removeChild(link);
                });
            });

            // Utility function to convert string to ArrayBuffer
            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) {
                    view[i] = s.charCodeAt(i) & 0xFF;
                }
                return buf;
            }
        </script>