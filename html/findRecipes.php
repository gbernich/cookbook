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
if ( $_GET['compliances'] == "" ) {
	$compliances = [];
} else {
	$compliances = explode(',', $_GET['compliances']);
}
$ingredients = explode(',', $_GET['ingredients']);

if ( $criteria == "()" ) {
	$sql = "SELECT * FROM Recipe;";
} else {
	$sql = "SELECT * FROM Recipe WHERE ".$criteria;
}
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
	$compliance_count = 0;

	// loop through each match and count the compliance if it matches the users input
	if ( $compliance_result->num_rows > 0 ) {
		while($compliance_row = $compliance_result->fetch_assoc()) {
			if ( in_array($compliance_row['compliance_id'], $compliances) ) {
				$compliance_count++;
			}
		}
	}

	// if the recipe complies with all compliances, then display it
	echo "<p>".$compliance_count." ".sizeof($compliances)." ".$_GET['compliances']."</p>";
        if ( $compliance_count == sizeof($compliances) ) {
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
