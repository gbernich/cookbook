<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
</head>

<script type="text/javascript">
    function initPage() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("recipeTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "showAllRecipes.php", true);
        xmlhttp.send();
    }
    window.onload = initPage;
</script>

<script>
function updateTable(str) {
	console.log("updateTable");
	console.log(str);

    if (str.length == 0) {
        document.getElementById("recipeTable").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("recipeTable").innerHTML = this.responseText;
            }
        };
        //xmlhttp.open("GET", "findRecipes.php?compliance=" + str, true);
        xmlhttp.open("GET", "findRecipes.php?criteria=" + str, true);
        xmlhttp.send();
    }
}
</script>
</head>
<body>

	<table>
	<tr><td>Filters</td></tr>

	<tr><td><input type="checkbox" id="compliance_whole30" onclick="updateTable('compliance_whole30 = true');">Whole30</td></tr>
	</table>


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

$sql = "SELECT * FROM Compliance";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Table Header
    // Table end
    echo "</table>";

} else {
    echo "0 results";
}
$conn->close();
?> 

<br></br>

<div id="recipeTable"></div>

</body>
</html> 
