<?php
// Get input data from the form
$destination = $_POST['destination'];
$seat_capacity = $_POST['seat_capacity'];
$driver_gender = $_POST['driver_gender'];

// Call the Python script with input parameters
$command = escapeshellcmd('python predict.py');
$output = shell_exec($command);

// Output the result
echo "<p>Predicted result: $output</p>";
?>
