 
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
//$criteria = $_GET['criteria'];
$sql = "INSERT INTO Recipe (name, description, prep_time, cook_time, total_time, hot_cold, compliance_whole30, compliance_meatless, compliance_other, meal_type)
			VALUES('Boiled Egg', 'A single boiled egg', 5, 15, 20, 'COLD', true, true, false, 'LUNCH');";
//$sql = "INSERT INTO Recipe (name) VALUES ('Test Recipe');";
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
	<input type="text" name="title" placeholder="Title of the post" required>
	<textarea cols="40" placeholder="Post Content" rows="8" name="post_content" required></textarea>
	<button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
