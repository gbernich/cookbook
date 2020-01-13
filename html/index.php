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
function updateTable() {
	console.log("updateTable");
//        document.getElementById("recipeTable").innerHTML = "<p>Hi</p>";

//    if (str.length == 0) {
//        document.getElementById("recipeTable").innerHTML = "";
//        return;
//    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("recipeTable").innerHTML = this.responseText;
            }
        };
        //xmlhttp.open("GET", "findRecipes.php?compliance=" + str, true);
        xmlhttp.open("GET", "findRecipes.php", true);
        xmlhttp.send();
//    }
}
</script>
</head>
<body>
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
    echo "<table>";
    echo "<tr>";
    echo "<td></td>";
    echo "<td>Compliance</td>";
    echo "</tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
//        echo "<td><input type=\"checkbox\" id=\"compliance_.$row[id]\" onclick=\"updateTable(\"\")\"></td>";
        echo "<td><input type=\"checkbox\" id=\"compliance_.$row[id]\" onclick=\"updateTable()\"></td>";
        echo "<td>".$row[name]."</td>";
        echo "</tr>";
    }
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
