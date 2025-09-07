<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = (int)$_POST['age'];

    // Input validation using branching
    if (empty($name) || empty($email) || $age <= 0) {
        echo "Error: All fields are required, and age must be positive.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format.";
    } else {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO students (name, email, age) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $email, $age);
        if ($stmt->execute()) {
            echo "Student added successfully!";
            // Redirect to index.html after successful insertion
            header("Location: index.html");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>