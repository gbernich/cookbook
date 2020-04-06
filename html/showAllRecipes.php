<?php
    include 'util.php';

    // Create connection
    $conn = connect();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ( $_GET['isLarge'] == 1 ) {
        $phpFile = 'displayLarge.php';
    } else {
        $phpFile = 'display.php';
    }

    $sql = "SELECT * FROM Recipe";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        // Table Header
        echo "<table>";
        echo "<tr>";
        echo "<td>Recipe</td>";
        echo "<td>Prep Time</td>";
        echo "<td>Cook Time</td>";
        echo "<td>Calories</td>";
        echo "</tr>";

        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a href='http://cookbook.local/".$phpFile."?id=".$row[id]."'>".$row[name]."</a></td>";
            echo "<td>".$row[prep_time]."</td>";
            echo "<td>".$row[cook_time]."</td>";
            echo "<td>".$row[calories]."</td>";
            echo "</tr>";
        }
        // Table end
        echo "</table>";

    } else {
        echo "0 results";
    }
    $conn->close();
?> 
