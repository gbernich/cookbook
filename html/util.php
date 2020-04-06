<?php

    ///////////////////////////////////////////////////////////////////////////
    // Connects to the mysql database Cookbook.
    ///////////////////////////////////////////////////////////////////////////
    function connect()
    {
        $servername = "localhost";
        $username   = "cookbook";
        $password   = "password";
        $dbname     = "Cookbook";

        // Create connection
        $connection = new mysqli($servername, $username, $password, $dbname);

        return $connection;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Queries for recipe
    ///////////////////////////////////////////////////////////////////////////
    function query_recipe($connection, $id)
    {
        $sql = "SELECT * FROM Recipe WHERE id = ".$id.";";

        $result = $connection->query($sql);
        
        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Queries for recipe's compliances
    ///////////////////////////////////////////////////////////////////////////
    function query_recipe_compliances($connection, $id)
    {
        $sql = "
        SELECT r.name AS 'recipe', 
               c.name AS 'compliance' 
        FROM Recipe r 
        JOIN RecipeCompliance rc on r.id = rc.recipe_id
        LEFT OUTER JOIN Compliance c on c.id = compliance_id
        WHERE r.id = ".$id.";";

        $result = $connection->query($sql);

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Queries for recipe's ingredients
    ///////////////////////////////////////////////////////////////////////////
    function query_recipe_ingredients($connection, $id)
    {
        $sql = "
        SELECT r.name AS 'recipe', 
                ri.amount_whole AS 'amount_whole', 
                ri.amount_numerator AS 'amount_num', 
                ri.amount_denominator AS 'amount_den', 
                mu.name AS 'unit', 
                i.name AS 'ingredient', 
            p.name AS 'preparation'
        FROM Recipe r 
        JOIN RecipeIngredient ri on r.id = ri.recipe_id 
        JOIN Ingredient i on i.id = ri.ingredient_id 
        LEFT OUTER JOIN Preparation p on p.id = ri.preparation_id 
        LEFT OUTER JOIN Measure mu on mu.id = measure_id
        WHERE r.id = ".$id.";";

        $result = $connection->query($sql);

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Queries for recipe's instructions
    ///////////////////////////////////////////////////////////////////////////
    function query_recipe_instructions($connection, $id)
    {
        $sql = "SELECT * FROM RecipeInstruction WHERE recipe_id = ".$id.";";

        $result = $connection->query($sql);

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Queries for recipe's log entries
    ///////////////////////////////////////////////////////////////////////////
    function query_recipe_log($connection, $id)
    {
        $sql = "SELECT * FROM RecipeLog WHERE recipe_id = ".$id.";";

        $result = $connection->query($sql);

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Header
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_header($recipe)
    {
        if ($recipe->num_rows > 0) {

            $recipe = $recipe->fetch_assoc();

            echo "<h1>".$recipe[name]."</h1>";
            echo "<p>".$recipe[description]."</p><br>";

            echo "<table>";
            echo "<tr><td>Serves </td><td>".$recipe[servings] ."  </td></tr>";
            echo "<tr><td>Prep Time </td><td>".$recipe[prep_time] ." minutes</td></tr>";
            echo "<tr><td>Cook Time </td><td>".$recipe[cook_time] ." minutes</td></tr>";
            echo "<tr><td>Total Time</td><td>".$recipe[total_time]." minutes</td></tr>";
            echo "</table>";

        } else {
            echo "Recipe Not Found";
        }


    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Compliances
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_compliances($compliances)
    {
        echo "<br>";
        echo "<h3>Compliant with</h3>";

        if ($compliances->num_rows > 0) {

            // Table Header
            echo "<ul>";

            while($row = $compliances->fetch_assoc()) {
                echo "<li>".$row['compliance']."</li>";
            }

            // Table end
            echo "</ul>";

        } else {
            echo "0 results";
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Ingredients
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_ingredients($ingredients)
    {
        echo "<br>";
        echo "<h3>Ingredients</h3>";

        if ($ingredients->num_rows > 0) {

            // Table Header
            echo "<ul>";

            while($row = $ingredients->fetch_assoc()) {

                // Preparation string
                if (is_null($row['preparation'])) {
                    $preparation_string = "";
                } elseif ($row['preparation'] == " ") {
                    $preparation_string = "";
                } else {
                    $preparation_string = "- ".$row['preparation'];
                }

                if ( $row['amount_num'] == 0 || $row['amount_den'] == 0 ) {
                    echo "<li>".$row['amount_whole']." ".$row['unit']." ".$row['ingredient']." ".$preparation_string."</li>";
                }
                elseif ( $row['amount_whole'] == 0 ) {
                    echo "<li>".$row['amount_num']."/".$row['amount_den']." ".$row['unit']." ".$row['ingredient']." ".$preparation_string."</li>";
                }
                else {
                    echo "<li>".$row['amount_whole']." ".$row['amount_num']."/".$row['amount_den']." ".$row['unit']." ".$row['ingredient']." ".$preparation_string."</li>";
                }
            }

            // Table end
            echo "</ul>";

        } else {
            echo "0 results";
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Instructions
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_instructions($instructions)
    {
        echo "<br>";
        echo "<h3>Instructions</h3>";

        if ($instructions->num_rows > 0) {

            // Table Header
            echo "<ul>";

            while($row = $instructions->fetch_assoc()) {
                echo "<li>".$row[instruction]."</li>";
            }

            // Table end
            echo "</ul>";

        } else {
            echo "0 results";
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Log
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_log($log_entries)
    {
        echo "<br>";
        echo "<h3>Log</h3>";

        if ($log_entries->num_rows > 0) {

            // Table Header
            echo "<table><tr><th>Date</th><th>Notes</th></tr>";

            while($row = $log_entries->fetch_assoc()) {
                echo "<tr><td>".$row[cook_date]."</td><td>".$row[notes]."</td></tr>";
            }

            // Table end
            echo "</table>";

        } else {
            echo "0 results";
        }
    }




?>