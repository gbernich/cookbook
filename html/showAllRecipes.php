<?php
$servername = "localhost";
$username   = "cookbook";
$password   = "password";
$dbname     = "Cookbook";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Recipe";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Table Header
    echo "<table>";
    echo "<tr>";
    echo "<td>Recipe</td>";
    echo "<td>Description</td>";
    echo "</tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row[name]."</td>";
        echo "<td>".$row[description]."</td>";
        echo "</tr>";
    }
    // Table end
    echo "</table>";

} else {
    echo "0 results";
}
$conn->close();
?> 
