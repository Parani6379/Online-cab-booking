<!DOCTYPE html>
<html>
<head>
    <title>Send SMS</title>
    <style>
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
    // Check if phone number and message are provided
    if (!empty($_POST["phoneNumber"]) && !empty($_POST["message"])) {
        $phoneNumber = $_POST['phoneNumber'];
        $messageContent = $_POST['message'];

        // SMS API integration
        $url = "https://www.fast2sms.com/dev/bulkV2";

        // Set up the request headers
        $headers = array(
            'authorization: AZekhLIIu65nuGDB8HEFOYFr7eSkynlvjb4CvdoM0iizJankXsQPw8bG1Azo',
            'Content-Type: application/x-www-form-urlencoded',
            'cache-control: no-cache'
        );

        // Set up the request body (payload)
        $data = array(
            'sender_id' => 'parani travels',
            'message' => $messageContent,
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
            $message = 'Message sent successfully.';
            $css_class = 'success';
        } else {
            // Print an error message
            $message = 'Failed to send message.';
            $css_class = 'error';
        }
    } else {
        // Phone number and message are required
        $message = 'Phone number and message are required.';
        $css_class = 'error';
    }
}
?>

<div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="phoneNumber">Enter Mobile Number:</label><br>
        <input type="text" id="phoneNumber" name="phoneNumber" required><br><br>
        <label for="message">Enter Message:</label><br>
        <textarea id="message" name="message" required></textarea><br><br>
        <input type="submit" value="Send Message">
    </form>
</div>

<?php if (!empty($message)): ?>
    <div class="<?php echo $css_class; ?>"><?php echo $message; ?></div>
<?php endif; ?>

</body>
</html>
