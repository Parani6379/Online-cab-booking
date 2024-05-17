<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cab - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        form {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"] {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Cab - Admin Panel</h2>
        
        <!-- PHP code to add cab -->
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

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $destination = sanitize_input($_POST["destination"]);
            $seatCapacity = sanitize_input($_POST["seatCapacity"]);
            $driverGender = sanitize_input($_POST["driverGender"]);
            $price = sanitize_input($_POST["price"]);
            $carModel = sanitize_input($_POST["carModel"]);
            $cabName = sanitize_input($_POST["cabName"]);

            // Insert cab details into database
            $sql = "INSERT INTO cab_info (destination, seat_capacity, driver_gender, price, car_model, cab_name) VALUES ('$destination', '$seatCapacity', '$driverGender', '$price', '$carModel', '$cabName')";

            if ($conn->query($sql) === TRUE) {
                echo "<p class='success-message'>Cab added successfully</p>";
            } else {
                echo "<p class='error-message'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }

        $conn->close();
        ?>

        <!-- Add Cab Form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required>
            
            <label for="seatCapacity">Seat Capacity:</label>
            <input type="number" id="seatCapacity" name="seatCapacity" required>
            
            <label for="driverGender">Driver Gender:</label>
            <input type="text" id="driverGender" name="driverGender" required>
            
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
            
            <label for="carModel">Car Model:</label>
            <input type="text" id="carModel" name="carModel" required>
            
            <label for="cabName">Cab Name:</label>
            <input type="text" id="cabName" name="cabName" required>
            
            <input type="submit" value="Add Cab">
        </form>
    </div>
</body>
</html>
