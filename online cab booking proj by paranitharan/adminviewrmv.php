<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            display: inline-block;
        }
        input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #c82333;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>

        <!-- PHP code for removing records -->
        <?php
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

        // Function to sanitize input
        function sanitize_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Fetch data from pickup_details table
        $sql = "SELECT booking_id, pickup_time, pickup_location, pickup_date, phone_number FROM pickup___details";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row in a table
            echo "<table border='1'>";
            echo "<tr><th>Booking ID</th><th>Pickup Time</th><th>Pickup Location</th><th>Pickup Date</th><th>Phone Number</th><th>Action</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["booking_id"]."</td>";
                echo "<td>".$row["pickup_time"]."</td>";
                echo "<td>".$row["pickup_location"]."</td>";
                echo "<td>".$row["pickup_date"]."</td>";
                echo "<td>".$row["phone_number"]."</td>";
                echo "<td><form method='post' action='".$_SERVER['PHP_SELF']."'><input type='hidden' name='remove_booking_id' value='".$row["booking_id"]."'><input type='submit' class='remove-btn' value='Remove'></form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        // Remove record if remove_booking_id is set
        if(isset($_POST['remove_booking_id'])) {
            $remove_booking_id = sanitize_input($_POST['remove_booking_id']);
            $sql_remove = "DELETE FROM pickup___details WHERE booking_id = '$remove_booking_id'";
            if ($conn->query($sql_remove) === TRUE) {
                echo "<p class='success-message'>Record with Booking ID $remove_booking_id removed successfully</p>";
                // Refresh the page after removal
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "<p class='error-message'>Error removing record: " . $conn->error . "</p>";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
