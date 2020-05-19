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
    // Remove Recipe
    ///////////////////////////////////////////////////////////////////////////
    function delete_recipe($connection, $id)
    {   
        $sql = "DELETE FROM RecipeCompliance WHERE recipe_id = ".$id.";";
        $result = $connection->query($sql);
        
        $sql = "DELETE FROM RecipeIngredient WHERE recipe_id = ".$id.";";
        $result = $connection->query($sql);
        
        $sql = "DELETE FROM RecipeInstruction WHERE recipe_id = ".$id.";";
        $result = $connection->query($sql);
        
        $sql = "DELETE FROM RecipeLog WHERE recipe_id = ".$id.";";
        $result = $connection->query($sql);
        
        $sql = "DELETE FROM Recipe WHERE id = ".$id.";";
        $result = $connection->query($sql);
        
        echo "Done deleting recipe " . $id ;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    // Remove Recipe, Keep Log
    ///////////////////////////////////////////////////////////////////////////
    function delete_recipe_for_replacement($connection, $id)
    {   
        $sql = "DELETE FROM RecipeCompliance WHERE recipe_id = ".$id.";";
        $result = $connection->query($sql);
        
        $sql = "DELETE FROM RecipeIngredient WHERE recipe_id = ".$id.";";
        $result = $connection->query($sql);
        
        $sql = "DELETE FROM RecipeInstruction WHERE recipe_id = ".$id.";";
        $result = $connection->query($sql);
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
    function display_recipe_header($recipe, $compliances)
    {
        if ($recipe->num_rows > 0) {

            $recipe = $recipe->fetch_assoc();

            echo "<h1>".$recipe[name]."</h1>";
            echo "<h5>".$recipe[description]."</h5><br>";

            if ($compliances->num_rows > 0) {
                echo "<div class='row'>";
                while($row = $compliances->fetch_assoc()) {
                    echo "<h4><span class='label label-primary' style='background-color: #777; border-radius: .25em; padding: .2em .6em .3em; margin-left:15px'>".$row['compliance']."</span></h4>";
                }
                echo "</div><br>";
            }

            echo "<h5>Serves ".$recipe[servings]."</h5>";
            echo "<h5>Prep Time ".$recipe[prep_time]." </h5>";
            echo "<h5>Cook Time ".$recipe[cook_time]." </h5>";
            echo "<h5>Total Time ".$recipe[total_time]." </h5>";


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
        // echo "<h3>Compliant with</h3>";

        if ($compliances->num_rows > 0) {


            echo "<div class='row'>";
            while($row = $compliances->fetch_assoc()) {
                echo "<h4><span class='label label-primary' style='background-color: #777; border-radius: .25em; padding: .2em .6em .3em; margin-left:15px'>".$row['compliance']."</span></h4>";
            }
            echo "</div>";

        }
    }


    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Ingredients
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_ingredients($ingredients)
    {
        echo "<br>";
        // echo "<h3>Ingredients</h3>";

        if ($ingredients->num_rows > 0) {

            // Table Header
            echo "<ul style='list-style-type: none; padding-left: 0'>";

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
            echo "No Ingredients!";
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Instructions
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_instructions($instructions)
    {
        echo "<br>";
        // echo "<h3>Instructions</h3>";

        if ($instructions->num_rows > 0) {

            // Table Header
            echo "<ol style='padding-left: 15px'>";

            while($row = $instructions->fetch_assoc()) {
                echo "<li>".$row[instruction]."</li>";
            }

            // Table end
            echo "</ol>";

        } else {
            echo "No instructions!";
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Recipe Log
    ///////////////////////////////////////////////////////////////////////////
    function display_recipe_log($log_entries)
    {
        echo "<br>";
        // echo "<h3>Log</h3>";

        if ($log_entries->num_rows > 0) {

            // Table Header
            echo "<table class='table table-hover' style='table-layout: auto;'><tr><th>Date</th><th>Notes</th></tr>";

            while($row = $log_entries->fetch_assoc()) {
                $tmp_date = date_format(date_create($row[cook_date]),"n/j/y");
                echo "<tr><td>".$tmp_date."</td><td>".$row[notes]."</td></tr>";
            }

            // Table end
            echo "</table>";

        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Compliance Checkboxes
    ///////////////////////////////////////////////////////////////////////////
    function display_compliance_checkboxes($connection)
    {
        $sql = "SELECT * FROM Compliance ORDER BY name";
        $result = $connection->query($sql);
        
        if ($result->num_rows > 0) {
        	echo "<h3>Compliances</h3><table>";
                while($row = $result->fetch_assoc()) {
        		echo "<tr><td><input name='compliance' type='checkbox' value=".$row['id']." onclick='updateTable();' >".$row['name']."</input></td></tr>\n";
        	}
        	echo "</table>";
        } else {
            echo "No compliances yet";
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Ingredient Checkboxes
    ///////////////////////////////////////////////////////////////////////////
    function display_ingredient_checkboxes($connection)
    {
        $sql = "SELECT * FROM Ingredient ORDER BY name";
        $result = $connection->query($sql);
        
        if ($result->num_rows > 0) {
        	echo "<h3>Ingredients</h3><table>";
                while($row = $result->fetch_assoc()) {
        		echo "<tr><td><input name='ingredient' type='checkbox' value=".$row['id']." onclick='updateTable();' >".$row['name']."</input></td></tr>\n";
        	}
        	echo "</table>";
        } else {
            echo "No ingredients yet";
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Display Form <field name> - these functions is reused by addRecipe.php
    //.                            and editRecipe.php
    ///////////////////////////////////////////////////////////////////////////
    function display_form_recipe_name($value)
    {
        $tmp = '';
        if ($value != ''){$tmp = " value='".$value."' ";} // format value field
        echo "<input type='text' name='name' placeholder='Recipe Name *' ".$tmp." maxlength='80' size='80' required><br><br>";
    }
    
    function display_form_recipe_description($value)
    {
        echo "<textarea cols='80' placeholder='Recipe Description *' rows='5' name='description' maxlength='250' required>". $value ."</textarea><br><br>";
    }
    
    function display_form_recipe_servings($value)
    {
        $tmp = '';
        if ($value != ''){$tmp = " value='".$value."' ";} // format value field
        echo "<input type='text' name='servings' placeholder='Servings *' ".$tmp." size='10' pattern='[0-9]+' required>";
    }
    
    function display_form_recipe_prep_time($value)
    {
        $tmp = '';
        if ($value != ''){$tmp = " value='".$value."' ";} // format value field
        echo "<input type='text' name='prep_time' placeholder='Prep Time (min)' ".$tmp." size='15' pattern='[0-9]*' required>";
    }
    
    function display_form_recipe_cook_time($value)
    {
        if ($value != ''){$tmp = " value='".$value."' ";} // format value field
        echo "<input type='text' name='cook_time' placeholder='Cook Time (min)' ".$tmp." size='15' pattern='[0-9]*' required><br><br>";
    }
    
    function display_form_recipe_hot_cold($value)
    {
        $hot_checked  = "";
        $cold_checked = "";
        if ($value == 'hot' ){$hot_checked  = "checked";}
        if ($value == 'cold'){$cold_checked = "checked";}
        echo "<input type='radio' name='hot_cold' value='HOT'  ".$hot_checked."  /> <span>Hot</span>";
        echo "<input type='radio' name='hot_cold' value='COLD' ".$cold_checked." /> <span>Cold</span><br><br>";
    }
    
    function display_form_recipe_meal_type($value)
    {
        $breakfast_checked  = "";
        $lunch_checked      = "";
        $dinner_checked     = "";
        $dessert_checked    = "";
        if ($value == 'breakfast' ){$breakfast_checked  = "checked";}
        if ($value == 'lunch'     ){$lunch_checked      = "checked";}
        if ($value == 'dinner'    ){$dinner_checked     = "checked";}
        if ($value == 'dessert'   ){$dessert_checked    = "checked";}
        echo "<input type='radio' name='meal_type' value='BREAKFAST' ".$breakfast_checked." /> <span>Breakfast</span>";
        echo "<input type='radio' name='meal_type' value='LUNCH' ".$lunch_checked." /> <span>Lunch</span>";
        echo "<input type='radio' name='meal_type' value='DINNER' ".$dinner_checked." /> <span>Dinner</span>";
        echo "<input type='radio' name='meal_type' value='DESSERT' ".$dessert_checked." /> <span>Dessert</span><br><br>";
    }
    
    function display_form_recipe_compliances($values)
    {
        $tmp = '';
        if (sizeof($values) > 0){$tmp = join("\n", $values);}
        echo "<textarea cols='30' placeholder='Compliance: one per line' rows='5' name='compliances'>".$tmp."</textarea><br><br>";
    }
    
    function display_form_recipe_ingredients($values)
    {
        $tmp = '';
        if (sizeof($values) > 0){$tmp = join("\n", $values);}
        echo "<textarea cols='80' placeholder='Ingredient: amount, measurement, ingredient *' rows='10' name='ingredients' pattern='[0-9]+\s+[A-Za-z]+\s+[\,]{1}\s+[A-Za-z\s]+' required>".$tmp."</textarea><br><br>";
    }
    
    function display_form_recipe_instructions($values)
    {
        $tmp = '';
        if (sizeof($values) > 0){$tmp = implode("\n", $values);}
        echo "<textarea cols='80' placeholder='Instruction: one per line *' rows='10' name='instructions' required>".$tmp."</textarea><br><br>";
    }
    
    function display_form_recipe_nutrition($values)
    {
        $tmp = [];
        foreach ($values as $value) {
            if ($value != ''){
                $tmp[] = "value='".$value."'";
            } else {
                $tmp[] = '';
            }
        }
        echo "<input type='text' name='calories'      placeholder='Calories'          maxlength='10' size='20' pattern='[0-9]*' ".$tmp[0]."><span>Calories</span><br>";
        echo "<input type='text' name='total_fat'     placeholder='Total Fat (g)'     maxlength='10' size='20' pattern='[0-9]*' ".$tmp[1]."><span>Total Fat (g)</span><br>";
        echo "<input type='text' name='saturated_fat' placeholder='Saturated Fat (g)' maxlength='10' size='20' pattern='[0-9]*' ".$tmp[2]."><span>Saturated Fat (g)</span><br>";
        echo "<input type='text' name='cholesterol'   placeholder='Cholesterol (mg)'  maxlength='10' size='20' pattern='[0-9]*' ".$tmp[3]."><span>Cholesterol (mg)</span><br>";
        echo "<input type='text' name='sodium'        placeholder='Sodium (mg)'       maxlength='10' size='20' pattern='[0-9]*' ".$tmp[4]."><span>Sodium (mg)</span><br>";
        echo "<input type='text' name='carbohydrates' placeholder='Carbohydrates (g)' maxlength='10' size='20' pattern='[0-9]*' ".$tmp[5]."><span>Carbohydrates (g)</span><br>";
        echo "<input type='text' name='fiber'         placeholder='Fiber (g)'         maxlength='10' size='20' pattern='[0-9]*' ".$tmp[6]."><span>Fiber (g)</span><br>";
        echo "<input type='text' name='sugar'         placeholder='Sugar (g)'         maxlength='10' size='20' pattern='[0-9]*' ".$tmp[7]."><span>Sugar (g)</span><br>";
        echo "<input type='text' name='protein'       placeholder='Protein (g)'       maxlength='10' size='20' pattern='[0-9]*' ".$tmp[8]."><span>Protein (g)</span><br><br>";
    }

?>