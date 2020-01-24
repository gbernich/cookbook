<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
</head>

<body>

<?php
$servername = "localhost";
$username   = "cookbook";
$password   = "password";
$dbname     = "Cookbook";

$id = $_GET['id'];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Display Recipe Name
$sql = "SELECT * FROM Recipe WHERE id = ".$id.";";
$result = $conn->query($sql);
if ($result->num_rows > 0) {

	$row = $result->fetch_assoc();

	echo "<h1>".$row[name]."</h1>";
	echo "<p>".$row[description]."</p><br>";

	echo "<table>";
	echo "<tr><td>Prep Time </td><td>".$row[prep_time] ." minutes</td></tr>";
	echo "<tr><td>Cook Time </td><td>".$row[cook_time] ." minutes</td></tr>";
	echo "<tr><td>Total Time</td><td>".$row[total_time]." minutes</td></tr>";
	echo "</table>";

} else {
    echo "Recipe Not Found";
}
/*
$sql = "SELECT * FROM RecipeIngredient WHERE id = ".$id.";";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	// Table Header
	echo "<ul>";

	while($row = $result->fetch_assoc()) {
		echo "<li> "
	}

	// Table end
	echo "</ul>";

} else {
    echo "0 results";
}

*/
$conn->close();
?> 

<br></br>

<div id="recipeTable"></div>

</body>
</html> 
