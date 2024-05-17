<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cab Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .confirmation {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;-
        }
        h2 {
            color: #333;
            margin-top: 0;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="time"], input[type="date"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="datetime-local"]:focus, input[type="date"]:focus {
            outline: none;
            border-color: #007bff;
        }
        button[type="submit"] {
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="confirmation">
        <h2>ENTER YOUR INFORMATION TO COMPLETE THE CAB BOOKING</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="phoneNumber" placeholder="Enter your phone number">
            <input type="time" name="pickupTime" placeholder="Select pickup time">
            <input type="text" name="pickupLocation" placeholder="Enter pickup location/Address">
            <input type="date" name="pickupDate" placeholder="Select pickup date">
            <button type="submit">CONFIRM</button>
        </form>
        <?php
        // Initialize variables for message and CSS class
        $message = '';
        $css_class = '';

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if phone number is provided
            if (!empty($_POST["phoneNumber"])) {
                // Database connection parameters
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

                // Retrieve form data
                $phoneNumber = $_POST['phoneNumber'];
                $pickupTime = $_POST['pickupTime'];
                $pickupLocation = $_POST['pickupLocation'];
                $pickupDate = $_POST['pickupDate'];

                // Prepare SQL statement to insert data into pickup__details table
                $sql = "INSERT INTO pickup___details (pickup_time, pickup_location, pickup_date, phone_number) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    // Error preparing SQL statement
                    $message = 'Error preparing SQL statement: ' . $conn->error;
                    $css_class = 'error';
                } else {
                    // Bind parameters and execute the statement
                    $stmt->bind_param("ssss", $pickupTime, $pickupLocation, $pickupDate, $phoneNumber);

                    if ($stmt->execute()) {
                        // Data inserted successfully
                        $message = 'Data stored successfully.';
                        $css_class = 'success';

                        // Retrieve the booking ID
                        $booking_id = $stmt->insert_id;

                        // SMS API integration
                        $url = "https://www.fast2sms.com/dev/bulkV2";

                        // Set up the request headers
                        $headers = array(
                            'authorization: VMpgFPaBhT31xk5W4Gc20YtidrOELqZunJwl6AzCI8ejDoS7KsXW46d7isDUSmf2Toy31JPrRvkYEjNl',
                            'Content-Type: application/x-www-form-urlencoded',
                            'cache-control: no-cache'
                        );

                        // Set up the request body (payload)
                        $data = array(
                            'sender_id' => 'parani travels',
                            'message' => 'Your cab booked successfully. Booking ID: ' . $booking_id . ',Pickup Time: ' . $pickupTime . ', Pickup Location: ' . $pickupLocation . ', Pickup Date: ' . $pickupDate,
                            'language' => 'english',
                            'route' => 'q',
                            'numbers' => $phoneNumber // Using the submitted phone number here
                        );

                        // Convert data array into URL-encoded query string
                        $data_string = http_build_query($data);

                        // Initialize cURL session
                        $ch = curl_init();

                        // Set cURL options
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        // Execute cURL request
                        $response = curl_exec($ch);

                        // Close cURL session
                        curl_close($ch);

                        // Check if the request was successful (response code 200)
                        if ($response !== false) {
                            // Parse the response
                            $parsed_response = json_decode($response, true);
                            // If the SMS is sent successfully
                            if (isset($parsed_response['return']) && $parsed_response['return'] === true) {
                                // SMS sent successfully
                                $message .= ' SMS sent successfully.';
                                $css_class = 'success';
                            } else {
                                // Error sending SMS
                                $message .= ' Failed to send SMS.';
                                $css_class = 'error';
                            }
                        } else {
                            // Failed to send SMS
                            $message .= ' Failed to send SMS.';
                            $css_class = 'error';
                        }
                    } else {
                        // Error executing SQL statement
                        $message = 'Error storing data: ' . $stmt->error;
                        $css_class = 'error';
                    }

                    // Close statement
                    $stmt->close();
                }

                // Close connection
                $conn->close();
            } else {
                // Phone number is required
                $message = 'Phone number is required.';
                $css_class = 'error';
            }
            // Display message for SMS sent successfully
            ?>
            <div class="<?php echo $css_class; ?>">
                <?php echo $message; ?>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
