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


$result = $conn->query($sql);
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

	<button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
