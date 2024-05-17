<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f2f2f2;
    }
    .container {
        width: 80%;
        margin: 50px auto;
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    h2 {
        margin-top: 0;
        text-align: center;
    }
    .row {
        text-align: center; /* Center the items in the row */
    }
    .count-container {
        text-align: center;
        margin-bottom: 20px;
        display: inline-block;
        margin-right: 20px; /* Add right margin for gap */
    }
    .count-container:last-child {
        margin-right: 0; /* Remove right margin for last container */
    }
	
    
    .count {
        font-size: 48px;
        margin: 0;
        font-weight: bold;
        color: #007bff;
        animation: countUp 1s ease-in-out;
    }
    .count-label {
        font-size: 24px;
        color: #333;
        margin-top: 10px;
    }
    .count-image {
        display: block;
        margin: 0 auto;
        width: 100px;
        height: 100px;
        background-image: url('taxi.png'); /* Replace with your cab icon */
        background-size: cover;
    }
    .count-image.customer {
        background-image: url('customer.png'); /* Replace with your customer icon */
    }
    .btn-container {
        text-align: center;
padding-top:10px;
    }
    .btn {
        padding: 10px 20px;
        margin: 10px;
        font-size: 16px;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    .btn:hover {
        background-color: #0056b3;
    }
    @keyframes countUp {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Admin Panel</h2>
        <div class="row">
            <div class="count-container">
                <div class="count-image"></div>
                <div class="count"><?php echo getCabCount(); ?></div>
                <div class="count-label">Cabs Available</div>
            </div>
            <div class="count-container">
                <div class="count-image customer"></div>
                <div class="count"><?php echo getCustomerCount(); ?></div>
                <div class="count-label">Customers Booked</div>
            </div>
        </div>
        <div class="btn-container">
            <a href="admin cab.php" class="btn">Add Cab</a>
            <a href="adminviewrmv.php" class="btn">View Customer Details / Remove </a>
        </div>
    </div>
<?php
    function getCabCount() {
        // Connect to your MySQL database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cabbooking";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to get cab count
        $sql = "SELECT COUNT(*) AS cab_count FROM cab_info";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["cab_count"];
        } else {
            return "0";
        }

        $conn->close();
    }

    function getCustomerCount() {
        // Connect to your MySQL database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cabbooking";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to get customer count
        $sql = "SELECT COUNT(*) AS customer_count FROM pickup___details";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["customer_count"];
        } else {
            return "0";
        }

        $conn->close();
    }
    ?>

</body>
</html>
