<?php

require_once __DIR__ . '/vendor/autoload.php';


use Phpml\Math\LinearProgramming\Problem;
use Phpml\Math\LinearProgramming\Constraint;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $Price_FlavoredMilk = $_POST['Price_FlavoredMilk'];
    $flavored_milk_quantity = $_POST['flavored_milk_quantity'];
    $total_members = $_POST['total_members'];

    // Define decision variables
    $x1 = $flavored_milk_quantity;
    // Define other decision variables similarly...

    // Define objective function coefficients
    $objectiveCoefficients = [
        $x1 => $Price_FlavoredMilk,
        // Define other objective function coefficients similarly...
    ];

    // Define constraints
    $constraints = [
        // Define constraints based on user input...
    ];

    // Create the optimization problem
    $problem = new Problem($objectiveCoefficients, $constraints, true);

    // Solve the optimization problem
    $result = $problem->solve();

    // Display the solution
    echo "Optimal solution:\n";
    foreach ($result as $variable => $value) {
        echo "$variable: $value\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linear Programming Optimization</title>
</head>

<body>
    <h1>Linear Programming Optimization</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="Price_FlavoredMilk">Price of Flavored Milk:</label>
        <input type="number" name="Price_FlavoredMilk" id="Price_FlavoredMilk" required>
        <br>
        <!-- Add similar input fields for other constants and variables -->
        <label for="flavored_milk_quantity">Flavored Milk Quantity:</label>
        <input type="number" name="flavored_milk_quantity" id="flavored_milk_quantity" required>
        <br>
        <!-- Add similar input fields for other decision variables -->
        <label for="total_members">Total Members:</label>
        <input type="number" name="total_members" id="total_members" required>
        <br>
        <button type="submit">Optimize</button>
    </form>
</body>

</html>