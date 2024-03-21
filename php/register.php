<?php
header("Content-Type: application/json"); // Set response content type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data using $_POST
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_1 = $_POST['password_1'];

    // Validate form data
    if (empty($username) || empty($email) || empty($password) || empty($password_1)) {
        $response = array('success' => false, 'message' => 'Please fill in all fields.');
    } elseif ($password != $password_1) {
        $response = array('success' => false, 'message' => 'Passwords do not match.');
    } else {
        // Connect to MySQL database
        $conn = mysqli_connect('localhost', 'root', '', 'login');

        if (!$conn) {
            $response = array('success' => false, 'message' => 'Database connection error: ' . mysqli_connect_error());
        } else {
            // Check if email already exists
            $result = $conn->prepare("SELECT * FROM users WHERE email=?");
            $result->bind_param("s", $email);
            $result->execute();
            $result->store_result();

            if ($result->num_rows > 0) {
                $response = array('success' => false, 'message' => 'Email already exists!');
            } else {
                // Prepare and execute INSERT query
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

                if (!$stmt) {
                    $response = array('success' => false, 'message' => 'Prepare statement error: ' . $conn->error);
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Bind parameters
                    $stmt->bind_param("sss", $username, $email, $password);

                    // Execute query
                    if ($stmt->execute()) {
                        $response = array('success' => true, 'message' => 'Registration successful!');
                    } else {
                        $response = array('success' => false, 'message' => 'Error inserting data into database: ' . $stmt->error);
                    }

                    // Close statement
                    $stmt->close();
                }
            }

            // Close database connection
            $conn->close();
        }
    }
} else {
    // Handle invalid request method
    $response = array('success' => false, 'message' => 'Invalid request method.');
}

// Send JSON response back to the AJAX call
echo json_encode($response);
?>
