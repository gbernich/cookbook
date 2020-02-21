<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
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
function getCheckedCheckboxesFor(checkboxName) {
    var checkboxes = document.querySelectorAll('input[name="' + checkboxName + '"]:checked'), values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });
    return values;
}

function updateTable() {
	console.log();

	var criteriaArray = new Array();

	var tmp = "";
	tmp = getCheckedCheckboxesFor('hot_cold').join(' or '); 		if (tmp) {criteriaArray.push(tmp);}
	tmp = getCheckedCheckboxesFor('meal_type').join(' or '); 		if (tmp) {criteriaArray.push(tmp);}
	var criteria = criteriaArray.join(') and (');
	console.log(criteria);

	compliances = getCheckedCheckboxesFor('compliance').join(',');
	console.log(compliances);

	ingredients = getCheckedCheckboxesFor('ingredient').join(',');
	console.log(ingredients);

	// Clean up criteria
	criteria = "(".concat(criteria, ")");
	console.log(criteria);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("recipeTable").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "findRecipes.php?criteria=" + criteria + "&compliances=" + compliances + "&ingredients=" + ingredients, true);
	xmlhttp.send();

}

</script>
</head>
<body>
	<h3>Meal Categories</h3>
	<table>
	<tr><td><input name="hot_cold"            type="checkbox" value="hot_cold='HOT'"           onclick="updateTable();"/>Hot</td>
	    <td><input name="hot_cold"            type="checkbox" value="hot_cold='COLD'"          onclick="updateTable();"/>Cold</td></tr>
	<tr><td><input name="meal_type"           type="checkbox" value="meal_type='BREAKFAST'"    onclick="updateTable();"/>Breakfast</td>
	    <td><input name="meal_type"           type="checkbox" value="meal_type='LUNCH'"        onclick="updateTable();"/>Lunch</td>
	    <td><input name="meal_type"           type="checkbox" value="meal_type='DINNER'"       onclick="updateTable();"/>Dinner</td>
	    <td><input name="meal_type"           type="checkbox" value="meal_type='DESSERT'"      onclick="updateTable();"/>Dessert</td></tr>
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

// Compliance checkboxes
$sql = "SELECT * FROM Compliance";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "<h3>Compliances</h3><table>";
        while($row = $result->fetch_assoc()) {
		echo "<tr><td><input name='compliance' type='checkbox' value=".$row['id']." onclick='updateTable();' >".$row['name']."</input></td></tr>\n";
	}
	echo "</table>";
} else {
    echo "No compliances yet";
}

// Ingredient checkboxes
$sql = "SELECT * FROM Ingredient";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "<h3>Ingredients</h3><table>";
        while($row = $result->fetch_assoc()) {
		echo "<tr><td><input name='ingredient' type='checkbox' value=".$row['id']." onclick='updateTable();' >".$row['name']."</input></td></tr>\n";
	}
	echo "</table>";
} else {
    echo "No compliances yet";
}


$conn->close();
?> 

<br></br>

<div id="recipeTable"></div>

</body>
</html> 
