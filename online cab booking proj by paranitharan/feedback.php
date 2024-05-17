<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cab Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        nav {
            background-color: #555;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        .booking-container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        textarea {
            width: 100%;
            height: 100px;
            resize: none;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .rating {
            margin-bottom: 20px;
        }

        .rating input[type="radio"] {
            display: none;
        }

        .rating label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
        }

        .rating input[type="radio"]:checked+label {
            color: #ffdd44;
        }

        button[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #444;
        }

        .feedback-list {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
           
        }

        .feedback-list h2 {
            color: #333;
        }

        .feedback-list p {
            margin-bottom: 5px;
        }

        .feedback-list p strong {
            font-weight: bold;
        }

        .feedback-list p:last-child {
            margin-bottom: 0;
        }

        .feedback-list hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 10px 0;
        }

        .no-feedback {
            color: #888;
            font-style: italic;
        }
        
    </style>
</head>

<body>
    
    <header>
        <h1>CAB BOOKING</h1>
    </header>

    <nav>
        <!-- Your navigation links here -->
    </nav>

    <div class="booking-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="feedback">Feedback:</label>
            <textarea id="feedback" name="feedback" required></textarea>

            <label for="rating">Rating:</label>
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="1">
                <label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="2">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="4">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="5">
                <label for="star1">&#9733;</label>
            </div>

            <button type="submit">Submit Feedback</button>
        </form>
    </div>

    <!-- PHP code to handle form submission and store feedback in database -->
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cabbooking";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $feedback = $_POST['feedback'];
        $rating = $_POST['rating'];

        $stmt = $conn->prepare("INSERT INTO feedback (feedback_text, rating) VALUES (?, ?)");
        $stmt->bind_param("si", $feedback, $rating);
        $stmt->execute();
        $stmt->close();

        $conn->close();
    }
    ?>

    <!-- Displaying old feedback -->
    <div class="feedback-list">
        <h2  style="text-align: center;">Reviews</h2>
        <?php
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT feedback_text, rating FROM feedback ORDER BY submission_date DESC";
        $result = $conn->query($sql);

        if ($result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p><strong></strong> " . $row['feedback_text'] . "</p>";
                echo "<p><strong>Rating:</strong> ";
                for ($i = 0; $i < $row['rating']; $i++) {
                    echo "&#9733;";
                }
                echo "</p><hr>";
            }
        } else {
            echo "No feedback yet.";
        }

        $conn->close();
        ?>
    </div>

</body>

</html>
