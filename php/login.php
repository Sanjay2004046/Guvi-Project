<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$email = $_POST['username'] ?? '';
$UserPassword = $_POST['password'] ?? '';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM users WHERE email = ? AND password = ?";
$statement = $conn->prepare($sql);

$statement->bind_param("ss", $email, $UserPassword);

if ($statement->execute()) {
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        echo 'success';
    } else {
        echo 'failure';
    }
} else {
    echo "Execution error: " . $statement->error;
}

$statement->close();
$conn->close();
?>