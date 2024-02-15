<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';
// Set coefficients of the objective function
$f_obj = array(15, 25, 15, 63, 45, 50, 10, 45);

// Set matrix corresponding to coefficients of constraints by rows
$f_con = array(
    array(1, 1, 0, 0, 0, 0, 0, 0), // demand product 1 upper bound
    array(1, 1, 0, 0, 0, 0, 0, 0), // demand product 1 lower bound
    array(0, 0, 1, 1, 0, 0, 0, 0), // demand product 2
    array(0, 0, 0, 0, 1, 1, 0, 0), // demand product 3
    array(0, 0, 0, 0, 0, 0, 1, 1), // demand product 4
    array(3, 0, 5, 0, 4, 0, 4, 0), // manual fabrication
    array(8, 0, 12, 0, 10, 0, 9, 0), // manual assembly
    array(2, 0, 3, 0, 5, 0, 2, 0), // manual test
    array(0, 5, 0, 6, 0, 7, 0, 4), // automatic fabrication
    array(0, 4, 0, 5, 0, 8, 0, 5) // automatic assembly/test
);

// Set inequality signs
$f_dir = array("<=", ">=", "<=", "<=", "<=", "<=", "<=", "<=", "<=", "<=");

// Set right-hand side coefficients
$f_rhs = array(3000, 1500, 2500, 2000, 3200, 16000, 30000, 15000, 24000, 20000);

// Final value (z)
$result = lp("max", $f_obj, $f_con, $f_dir, $f_rhs, TRUE);

// Variables final values
$solution = lp("max", $f_obj, $f_con, $f_dir, $f_rhs, TRUE)['solution'];

// Function for LP solving
function lp($type, $f_obj, $f_con, $f_dir, $f_rhs, $all_int)
{
    // Your LP solving logic here
}
