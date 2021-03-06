<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
    <meta name="viewport" content="width=device-width, initial-scale=0.95">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
</head>

<script type="text/javascript">
    function initPage() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("recipeTable").innerHTML = this.responseText;
            }
        };

        var isLarge = 0;
        if (window.innerWidth > 1000) {
            isLarge = 1;
        }
        console.log(isLarge);

        xmlhttp.open("GET", "showAllRecipes.php?isLarge=" + isLarge, true);
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

    var isLarge = 0;
    if (window.innerWidth > 1000) {
        isLarge = 1;
    }

	xmlhttp.open("GET", "findRecipes.php?criteria=" + criteria + "&compliances=" + compliances + "&ingredients=" + ingredients + "&isLarge=" + isLarge, true);
	xmlhttp.send();

}

</script>
</head>
<body style="margin-left:0; padding:10">
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
    include 'util.php';
    
    // Create connection
    $conn = connect();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get checkboxes
    display_compliance_checkboxes($conn);
    display_ingredient_checkboxes($conn);

    $conn->close();
?> 

<br></br>
<div id="recipeTable"></div>
<br></br>

<a target="_blank" href="http://cookbook.local/addRecipe.php">New Recipe</a>

</body>
</html> 
