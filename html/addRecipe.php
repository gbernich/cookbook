<?php 
$servername = "localhost";
$username   = "cookbook";
$password   = "password";
$dbname     = "Cookbook";

if(isset($_POST['submit']))
{

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// insert Recipe
$sql = "INSERT INTO Recipe (name, description, prep_time, cook_time, total_time, hot_cold, compliance_whole30, compliance_meatless, compliance_other, meal_type)
			VALUES('".$_POST['name']."',
                               '".$_POST['description']."',
                                ".$_POST['prep_time'].",
                                ".$_POST['cook_time'].",
                                ".$_POST['total_time'].",
                               '".$_POST['hot_cold']."',
                                ".$_POST['compliance_whole30'].",
                                ".$_POST['compliance_meatless'].",
                                ".$_POST['compliance_other'].",
                               '".$_POST['meal_type']."');";


$result    = $conn->query($sql);
$recipe_id = $conn->insert_id;

// Handle Recipe Instructions
$instructions = preg_split("/\r\n|\n|\r/", $_POST['instructions']);
foreach( $instructions as $instruction )
{
	$sql    = "INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (".$recipe_id.", '".$instruction."');";
	$result = $conn->query($sql);
}

// Handle Recipe Ingredients
$ingredientLines = preg_split("/\r\n|\n|\r/", strtolower($_POST['ingredients']));
foreach( $ingredientLines as $ingredientLine )
{
	// Parse ingredient line
	$ingredientArr = preg_split('/,/', $ingredientLine);
	$ingredient    = trim($ingredientArr[1]);

	$quantityArr = preg_split('/\s+/', trim($ingredientArr[0]), 2);
	$amount      = trim($quantityArr[0]);
	$measure     = trim($quantityArr[1]);

	// See if ingredient exists
	$sql    = "SELECT * FROM Ingredient WHERE name = '".$ingredient."';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// the ingredient exists, use this id
		$row = $result->fetch_assoc();
		$ingredient_id = $row["id"];
	} else {
		// the ingredient doesnt exist, add it, and use latest id
		$sql = "INSERT INTO Ingredient (name) VALUES ('".$ingredient."');";
		$result = $conn->query($sql);
		$ingredient_id = $conn->insert_id;
	}

	// See if measurement exists
	$sql    = "SELECT * FROM Measure WHERE name = '".$measure."';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// the measurement exists, use this id
		$row = $result->fetch_assoc();
		$measure_id = $row["id"];
	} else {
		// the measurement doesnt exist, add it, and use latest id
		$sql = "INSERT INTO Measure (name) VALUES ('".$measure."');";
		$result = $conn->query($sql);
		$measure_id = $conn->insert_id;
	}

	// Associate ingredient with recipe
	$sql    = "INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount) VALUES (".$recipe_id.", ".$ingredient_id.", ".$measure_id.", ".$amount.");";
	$result = $conn->query($sql);

}

// Close connection
$conn->close();

}
?>

<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
<title>Form site</title>
</head>
<body>
<form method="post" action="">
	<input type="text" name="name" placeholder="Recipe Name" required><br>

	<textarea cols="40" placeholder="Recipe Description" rows="8" name="description" required></textarea><br>

	<input type="text" name="prep_time" placeholder="Prep Time" ><br>

	<input type="text"  name="cook_time"  placeholder="Cook Time" ><br>

	<input type="text"  name="total_time" placeholder="Total Time" ><br>

	<input type="radio" name="hot_cold" value="HOT" checked /> <span>Hot</span>
	<input type="radio" name="hot_cold" value="COLD"        /> <span>Cold</span><br>

	<input type="radio" name="compliance_whole30"  value="true"          /> <span>WHOLE30</span>
	<input type="radio" name="compliance_whole30"  value="false" checked /> <span>No</span><br>

	<input type="radio" name="compliance_meatless" value="true"          /> <span>Meatless</span>
	<input type="radio" name="compliance_meatless" value="false" checked /> <span>No</span><br>

	<input type="radio" name="compliance_other"    value="true"          /> <span>Other</span>
	<input type="radio" name="compliance_other"    value="false" checked /> <span>No</span><br>

	<input type="radio" name="meal_type"    value="BREAKFAST"  checked /> <span>Breakfast</span>
	<input type="radio" name="meal_type"    value="LUNCH"              /> <span>Lunch</span>
	<input type="radio" name="meal_type"    value="DINNER"             /> <span>Dinner</span>
	<input type="radio" name="meal_type"    value="DESSERT"            /> <span>Dessert</span><br>

	<textarea cols="40" placeholder="Ingredients" rows="8" name="ingredients" required></textarea><br>

	<textarea cols="40" placeholder="Instructions" rows="8" name="instructions" required></textarea><br>

	<button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
