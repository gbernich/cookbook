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

$criteria = $_GET['criteria'];

//$sql = "SELECT * FROM Recipe WHERE ".$criteria;
$sql = "SELECT r.name AS 'recipe',
               r.hot_cold AS 'hot_cold',
               r.meal_type AS 'meal_type',
               r.prep_time AS 'prep_time',
               r.cook_time AS 'cook_time',
               r.calories AS 'calories',
               c.name AS 'compliance'
        FROM Recipe r
        LEFT JOIN RecipeCompliance rc on r.id = rc.recipe_id
        LEFT OUTER JOIN Compliance c on c.id = compliance_id
        WHERE ".$criteria.";";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Table Header
    echo "<table>";
    echo "<tr>";
    echo "<td>Recipe</td>";
    echo "<td>Prep Time</td>";
    echo "<td>Cook Time</td>";
    echo "<td>Calories</td>";
    echo "<td>Meal Type</td>";
    echo "<td>Hot/Cold</td>";
    echo "</tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
	echo "<td><a href='http://cookbook.local/display.php?id=".$row[id]."'>".$row['recipe']."</a></td>";
        echo "<td>".$row[prep_time]."</td>";
        echo "<td>".$row[cook_time]."</td>";
        echo "<td>".$row[calories]."</td>";
        echo "<td>".strtolower($row[meal_type])."</td>";
        echo "<td>".strtolower($row[hot_cold])."</td>";
        echo "</tr>";
    }
    // Table end
    echo "</table>";

} else {
    echo "0 results";
}
$conn->close();
?> 
