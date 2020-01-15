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
			VALUES('".$_POST['name']."', '".$_POST['description']."', 5, 15, 20, 'COLD', true, true, false, 'LUNCH');";


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
	<button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
