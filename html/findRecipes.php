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

$criteria = $_GET['criteria'];

$sql = "SELECT * FROM Recipe WHERE ".$criteria;
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Table Header
    echo "<table>";
    echo "<tr>";
    echo "<td>Recipe</td>";
    echo "<td>Description</td>";
    echo "<td>Prep Time</td>";
    echo "<td>Cook Time</td>";
    echo "<td>Total Time</td>";
    echo "</tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row[name]."</td>";
        echo "<td>".$row[description]."</td>";
        echo "<td>".$row[prep_time]."</td>";
        echo "<td>".$row[cook_time]."</td>";
        echo "<td>".$row[total_time]."</td>";
        echo "</tr>";
    }
    // Table end
    echo "</table>";

} else {
    echo "0 results";
}
$conn->close();
?> 