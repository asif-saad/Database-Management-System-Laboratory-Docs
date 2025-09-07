<?php
require 'db_connect.php';

$result = $conn->query("SELECT * FROM students");
if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Age</th></tr>";
    // Looping through database results
    while ($row = $result->fetch_assoc()) {
        // Using associative array to access row data
        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['email'] . "</td><td>" . $row['age'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No students found.";
}
$conn->close();
?>