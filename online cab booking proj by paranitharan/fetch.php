<style>
    /* Style for search results heading */
.search-results {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
}

/* Style for results container */
.results {
    display: flex; /* Use flexbox */
    flex-direction: column; /* Stack items vertically */
    align-items: center; /* Center items horizontally */
}

/* Style for individual result container */
.result {
    background-color: #f9f9f9;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    transition: transform 0.3s ease;
    width: 80%; /* Adjust the width as needed */
    max-width: 400px; /* Set maximum width */
}

/* Hover effect for individual result */
.result:hover {
    transform: scale(1.02);
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
}

/* Style for cab name */
.cab-name {
    font-size: 20px;
    color: #007bff;
    margin-bottom: 5px;
}

/* Style for car model, destination, price, seat capacity, and driver gender */
.car-model,
.destination,
.price,
.seat-capacity,
.driver-gender {
    margin-bottom: 5px;
    color: #555;
}

/* Style for the link to book cab */
.details-link {
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}

.details-link:hover {
    background-color: #0056b3;
}
</style>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    
</head>
<body>
<?php
function getCabDetails($destination, $seat_capacity, $driver_gender) {
    $name = "localhost";
    $username = "root";
    $password = "";
    $database = "cabbooking";

    // Create connection
    $conn = new mysqli($name, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to retrieve cab details based on criteria
    $sql = "SELECT * FROM cab_info WHERE destination = '$destination' AND seat_capacity = '$seat_capacity' AND driver_gender = '$driver_gender'";

    $result = $conn->query($sql);

    // Check if query was successful
    if ($result === false) {
        // Query failed, display or log the error
        echo "Error: " . $conn->error;
        return null;
    } else {
        // Query succeeded
        $cabDetails = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Store cab details in an array
                $cabDetails[] = $row;
            }
        }
        // Close connection
        $conn->close();

        return $cabDetails;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $destination = $_POST['destination'];
    $seat_capacity = $_POST['seat_capacity'];
    $driver_gender = $_POST['driver_gender'];

    // Call getCabDetails() function to retrieve cab details
    $cabs = getCabDetails($destination, $seat_capacity, $driver_gender);

    // Display search results
    if (!empty($cabs)) {
        echo "<h2>Search Results:</h2>";
        echo "<div class='results'>";
        foreach ($cabs as $cab) {
            echo "<div class='result'>";
            echo "<h3>" . $cab["cab_name"] . "</h3>";
            echo "<p><strong>Car Model:</strong> " . $cab["car_model"] . "</p>";
            echo "<p><strong>Destination:</strong> " . $cab["destination"] . "</p>";
            echo "<p><strong>Price per KM:</strong> $" . $cab["price"] . "</p>";
            echo "<p><strong>Seat Capacity:</strong> " . $cab["seat_capacity"] . "</p>";
            echo "<p><strong>Driver Gender:</strong> " . $cab["driver_gender"] . "</p>";
            echo "<a class='details-link' href='cab_confirm.php?cab_name=" . urlencode($cab["cab_name"]) . "&car_model=" . urlencode($cab["car_model"]) . "&price=" . urlencode($cab["price"]) . "&seat_capacity=" . urlencode($cab["seat_capacity"]) . "&driver_gender=" . urlencode($cab["driver_gender"]) . "'>BOOK CAB</a>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        // If no cabs match the criteria, display error message
        echo "<h2>Sorry! No cabs available matching your criteria.</h2>";
    }
}
?>
 

 </body>
</html>