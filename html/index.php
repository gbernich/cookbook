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

	tmp = getCheckedCheckboxesFor('compliance_whole30').join(' or '); 	if (tmp) {criteriaArray.push(tmp);}
	tmp = getCheckedCheckboxesFor('compliance_meatless').join(' or '); 	if (tmp) {criteriaArray.push(tmp);}
	tmp = getCheckedCheckboxesFor('compliance_other').join(' or '); 	if (tmp) {criteriaArray.push(tmp);}
	tmp = getCheckedCheckboxesFor('hot_cold').join(' or '); 		if (tmp) {criteriaArray.push(tmp);}
	tmp = getCheckedCheckboxesFor('meal_type').join(' or '); 		if (tmp) {criteriaArray.push(tmp);}

	var criteria = criteriaArray.join(') and (');

	console.log(criteria);

	// if string empty, reload all recipes
	if (criteria == "") {
		initPage();
		return;
	}

	// Clean up criteria
	criteria = "(".concat(criteria, ")");
	console.log(criteria);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("recipeTable").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "findRecipes.php?criteria=" + criteria, true);
	xmlhttp.send();

}

</script>
</head>
<body>

	<table>
	<tr><td>Filters</td></tr>
	<tr><td><input name="compliance_whole30"  type="checkbox" value="compliance_whole30=true"  onclick="updateTable();"/>Whole30</td></tr>
	<tr><td><input name="compliance_meatless" type="checkbox" value="compliance_meatless=true" onclick="updateTable();"/>Meatless</td></tr>
	<tr><td><input name="compliance_other"    type="checkbox" value="compliance_other=true"    onclick="updateTable();"/>Other</td></tr>
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
