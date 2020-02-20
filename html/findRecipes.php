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
$compliances = explode(',', $_GET['compliances']);

$sql = "SELECT * FROM Recipe WHERE ".$criteria;
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Table Header
    echo "<table>";
    echo "<tr>";
    echo "<td>Recipe</td>";
    echo "<td>Prep Time</td>";
    echo "<td>Cook Time</td>";
    echo "<td>Calories</td>";
    echo "<td>Meal Type</td>";
    echo "<td>Hot/Cold</td>";
    echo "</tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {

	//get compliances
	$sql = "SELECT * FROM RecipeCompliance WHERE recipe_id=".$row['id'].";";
	$compliance_result = $conn->query($sql);
	$count = 0;

	// loop through each match and count the compliance if it matches the users input
	while($compliance_row = $compliance_result->fetch_assoc()) {
		if ( in_array($compliance_row['compliance_id'], $compliances) ) {
			$count++;
		}
	}

	// if the recipe complies with all compliances, then display it
        if ( $count == sizeof($compliances) ) {
		echo "<tr>";
		echo "<td><a href='http://cookbook.local/display.php?id=".$row[id]."'>".$row['name']."</a></td>";
        	echo "<td>".$row[prep_time]."</td>";
        	echo "<td>".$row[cook_time]."</td>";
        	echo "<td>".$row[calories]."</td>";
        	echo "<td>".strtolower($row[meal_type])."</td>";
        	echo "<td>".strtolower($row[hot_cold])."</td>";
        	echo "</tr>";
	}
    }
    // Table end
    echo "</table>";

} else {
    echo "0 results";
}
$conn->close();
?> 
