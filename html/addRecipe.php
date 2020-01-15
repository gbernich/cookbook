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
                               '".$_POST['hot_cold']."', true, true, false, 'LUNCH');";


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
	<input type="text" name="cook_time" placeholder="Cook Time" ><br>
	<input type="text" name="total_time" placeholder="Total Time" ><br>
	<input type="radio" name="hot_cold" value="HOT" checked /> <span>Hot</span>
	<input type="radio" name="hot_cold" value="COLD" /> <span>Cold</span>
	<button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
