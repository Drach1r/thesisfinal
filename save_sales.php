<?php
session_start();
include 'db_connect.php';
$allowedUserTypes = array(1);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

$sales_list = isset($_SESSION['sales_list']) ? $_SESSION['sales_list'] : array();

$result = $conn->query("SELECT s.id, s.customer_id, i.product_id, i.qty, p.price, s.total FROM sales s INNER JOIN inventory i ON s.id = i.form_id INNER JOIN products p ON i.product_id = p.id");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $entry = array(
            'id' => $row['id'],
            'customer_id' => $row['customer_id'],
            'product_id' => $row['product_id'],
            'qty' => $row['qty'],
            'price' => $row['price'],
            'total' => $row['total']
        );
        $sales_list[] = $entry;
    }
}

function add_sales_entry(&$sales_list, $customer_id, $product_id, $qty, $price, $total)
{
    global $conn;
    $product = $conn->query("SELECT price FROM products WHERE id = '$product_id'")->fetch_assoc();
    $entry = array(
        "customer_id" => $customer_id,
        "product_id" => $product_id,
        "qty" => $qty,
        "price" => $product['price'],
        "total" => $total
    );
    $sales_list[] = $entry;
    $_SESSION['sales_list'] = $sales_list;
}

function calculateOverallTotalPrice($sales_list)
{
    $overallTotal = 0.0;
    foreach ($sales_list as $entry) {
        $quantity = (float) $entry['qty'];
        $selling_price = (float) $entry['price'];
        $total_amount = $quantity * $selling_price;
        $overallTotal += $total_amount;
    }
    return $overallTotal;
}

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM stock_in WHERE id=" . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $val) {
        $$k = $val;
    }
    $inv = $conn->query("SELECT * FROM inventory WHERE form_id=" . $_GET['id']);
}

$referenceId = rand(1000, 9999);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];

    $product_id = $_POST['product_id'];
    $form_id = isset($_GET['id']) ? $_GET['id'] : '';
    $availableQty = $conn->query("SELECT SUM(qty) as total_qty FROM inventory WHERE product_id = '$product_id' AND type = 1 AND stock_from = 1 AND form_id != '$form_id'")->fetch_assoc()['total_qty'];

    if ($qty > $availableQty) {
        $_SESSION['error_message'] = "Quantity exceeds available stock!";
        header("Location: c_sales.php");
        exit();
    }

    $total = $qty * $price;

    add_sales_entry($sales_list, $customer_id, $product_id, $qty, $price, $total);

    $_SESSION['success_message'] = "Item added to the sales list!";
    header("Location: c_sales.php");
    exit();
}

function delete_sales_entry(&$sales_list, $index)
{
    if (isset($sales_list[$index])) {
        unset($sales_list[$index]);
        $_SESSION['sales_list'] = $sales_list;
    }
}

if (isset($_GET['delete'])) {
    $deleteIndex = $_GET['delete'];
    delete_sales_entry($sales_list, $deleteIndex);
    $_SESSION['success_message'] = "Item deleted from the sales list!";
    header("Location: c_sales.php");
    exit();
}

$total_price = calculateOverallTotalPrice($sales_list);

// Save sales data to the database
if (isset($_POST['save_sales'])) {
    // Insert the sales information into the sales table
    $customer_id = $_POST['customer_id'];
    $sales_query = "INSERT INTO sales (customer_id, total) VALUES ('$customer_id', '$total_price')";
    $conn->query($sales_query);

    // Retrieve the last inserted sales ID
    $sales_id = $conn->insert_id;

    // Insert each sales item into the inventory table
    foreach ($sales_list as $entry) {
        $product_id = $entry['product_id'];
        $qty = $entry['qty'];
        $price = $entry['price'];

        $inventory_query = "INSERT INTO inventory (product_id, qty, price, form_id, type, stock_from) VALUES ('$product_id', '$qty', '$price', '$sales_id', 2, 2)";
        $conn->query($inventory_query);
    }

    // Clear the sales list and session variable
    $sales_list = array();
    $_SESSION['sales_list'] = array();

    $_SESSION['success_message'] = "Sales data saved successfully!";
    header("Location: c_sales.php");
    exit();
}
?>
