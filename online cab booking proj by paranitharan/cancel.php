<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking and Send SMS</title>
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
            width: 100%;
        }
        h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }
        button[type="submit"] {
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .notification-message {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<?php
// Initialize variables for message and CSS class
$message = '';
$css_class = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if phone number and booking ID are provided
    if (!empty($_POST["phoneNumber"]) && !empty($_POST["bookingId"])) {
        $phoneNumber = $_POST['phoneNumber'];
        $bookingId = $_POST['bookingId'];

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
            'message' => 'Your cab with booking ID ' . $bookingId . ' has been canceled successfully.',
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
            // Print the response
            $message = 'Your cab with booking ID ' . $bookingId . ' has been canceled successfully.';
            $css_class = 'success';
        } else {
            // Print an error message
            $message = 'Failed to send message. Please try again later.';
            $css_class = 'error';
        }
    } else {
        // Phone number and booking ID are required
        $message = 'Phone number and booking ID are required.';
        $css_class = 'error';
    }
}
?>

<div class="confirmation">
    <h2>Enter Your Phone Number and Booking ID to Cancel the Booking</h2>
    <form id="cancelForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
        <input type="text" name="phoneNumber" id="phoneNumber" placeholder="Enter your phone number" required>
        <input type="text" name="bookingId" id="bookingId" placeholder="Enter booking ID" required>
        <button type="submit">Cancel Booking</button>
    </form>
</div>

<script>
    function validateForm() {
        // Client-side validation
        var phoneNumber = document.getElementById("phoneNumber").value;
        var bookingId = document.getElementById("bookingId").value;

        if (!phoneNumber || !bookingId) {
            alert("Please fill out all fields.");
            return false;
        }
        return true;
    }
</script>

<?php if (!empty($message)): ?>
    <div class="<?php echo $css_class; ?>"><?php echo $message; ?></div>
<?php endif; ?>

</body>
</html>
