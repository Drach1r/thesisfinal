<?php
include 'db_connect.php';

if (isset($_POST["product_id"])) {
    $product_id = mysqli_real_escape_string($conn, $_POST["product_id"]);
    $sql = "SELECT pc.component_id, p.name AS component_name, pc.qty_required, pc.qty_unit 
            FROM product_components pc 
            INNER JOIN products p ON pc.component_id = p.id 
            WHERE pc.product_id = $product_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $components = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $components[] = array(
                'component_id' => $row['component_id'],
                'component_name' => $row['component_name'],
                'qty_required' => $row['qty_required'],
                'qty_unit' => $row['qty_unit']
            );
        }

        echo json_encode($components);
    } else {
        echo json_encode(array()); // Return an empty array if no components found
    }
}
