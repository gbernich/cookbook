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
// Ingredients
//$sql = "SELECT * FROM RecipeInstruction WHERE recipe_id = ".$id.";";
$sql = "
SELECT r.name AS 'recipe', 
        ri.amount_whole AS 'amount_whole', 
        ri.amount_numerator AS 'amount_num', 
        ri.amount_denominator AS 'amount_den', 
        mu.name AS 'unit', 
        i.name AS 'ingredient' 
FROM Recipe r 
JOIN RecipeIngredient ri on r.id = ri.recipe_id 
JOIN Ingredient i on i.id = ri.ingredient_id 
LEFT OUTER JOIN Measure mu on mu.id = measure_id
WHERE r.id = ".$id.";";

$result = $conn->query($sql);

echo "<br><br>";
echo "<h3>Ingredients</h3>";

if ($result->num_rows > 0) {

	// Table Header
	echo "<ul>";

	while($row = $result->fetch_assoc()) {
		if ( $row['amount_num'] == 0 || $row['amount_den'] == 0 ) {
			echo "<li>".$row['amount_whole']." ".$row['unit']." ".$row['ingredient']."</li>";
		}
		else {
			echo "<li>".$row['amount_whole']." ".$row['amount_num']."/".$row['amount_den']." ".$row['unit']." ".$row['ingredient']."</li>";
		}
	}

	// Table end
	echo "</ul>";

} else {
    echo "0 results";
}

// Instructions
$sql = "SELECT * FROM RecipeInstruction WHERE recipe_id = ".$id.";";
$result = $conn->query($sql);

echo "<br><br>";
echo "<h3>Instructions</h3>";

if ($result->num_rows > 0) {

	// Table Header
	echo "<ul>";

	while($row = $result->fetch_assoc()) {
		echo "<li>".$row[instruction]."</li>";
	}

	// Table end
	echo "</ul>";

} else {
    echo "0 results";
}


$conn->close();
?> 

<br></br>

<div id="recipeTable"></div>

</body>
</html> 
