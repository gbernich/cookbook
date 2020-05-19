<?php 
    include 'util.php';

	if(isset($_POST['submit']))
	{
	    // Create connection
	    $conn = connect();

	    // Check connection
	    if ($conn->connect_error) {
	        die("Connection failed: " . $conn->connect_error);
	    }

        // Delete Recipe
	    $recipe_id = $_GET['id'];
        delete_recipe_for_replacement($conn, $recipe_id);
        
	// Insert Recipe as a new recipe, with the same id
	$total_time = $_POST['prep_time'] + $_POST['cook_time'];

	/*$sql = "INSERT INTO Recipe (name, description, servings, prep_time, cook_time, total_time, hot_cold,  meal_type)
				VALUES(
				       '".$_POST['name']."',
                       '".$_POST['description']."',
                        ".$_POST['servings'].",
                        ".$_POST['prep_time'].",
                        ".$_POST['cook_time'].",
                        ".$total_time.",
                       '".$_POST['hot_cold']."',
                       '".$_POST['meal_type']."');";
                       */
    $sql = "UPDATE Recipe SET name='".$_POST['name']."' WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);
    $sql = "UPDATE Recipe SET description='".$_POST['description']."' WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);
    $sql = "UPDATE Recipe SET servings=".$_POST['servings']." WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);
    $sql = "UPDATE Recipe SET prep_time=".$_POST['prep_time']." WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);
    $sql = "UPDATE Recipe SET cook_time=".$_POST['cook_time']." WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);
    $sql = "UPDATE Recipe SET total_time=".$total_time." WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);
    $sql = "UPDATE Recipe SET hot_cold='".$_POST['hot_cold']."' WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);
    $sql = "UPDATE Recipe SET meal_type='".$_POST['meal_type']."' WHERE id=".$recipe_id.";";
    $result = $conn->query($sql);

	// Add Nutrition, if available
	if ( $_POST['calories'] 	!= "" ) { $sql = "UPDATE Recipe SET calories=".		$_POST['calories']." 		WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['total_fat'] 	!= "" ) { $sql = "UPDATE Recipe SET total_fat=".	$_POST['total_fat']." 		WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['saturated_fat'] 	!= "" ) { $sql = "UPDATE Recipe SET saturated_fat=".	$_POST['saturated_fat']." 	WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['cholesterol'] 	!= "" ) { $sql = "UPDATE Recipe SET cholesterol=".	$_POST['cholesterol']." 	WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['sodium'] 		!= "" ) { $sql = "UPDATE Recipe SET sodium=".		$_POST['sodium']." 		WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['carbohydrates'] 	!= "" ) { $sql = "UPDATE Recipe SET carbohydrates=".	$_POST['carbohydrates']." 	WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['fiber'] 		!= "" ) { $sql = "UPDATE Recipe SET fiber=".		$_POST['fiber']." 		WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['sugar'] 		!= "" ) { $sql = "UPDATE Recipe SET sugar=".		$_POST['sugar']." 		WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }
	if ( $_POST['protein'] 		!= "" ) { $sql = "UPDATE Recipe SET protein=".		$_POST['protein']." 		WHERE id=".$recipe_id.";"; $result = $conn->query($sql); }

	// See if compliance exists
	if ( trim($_POST['compliances']) != "" ) {
		$complianceLines = preg_split("/\r\n|\n|\r/", strtolower(trim($_POST['compliances'])));
		foreach( $complianceLines as $complianceLine )
		{
			$compliance = trim($complianceLine);
			$sql    = "SELECT * FROM Compliance WHERE name = '".$compliance."';";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				// the compliance exists, use this id
				$row = $result->fetch_assoc();
				$compliance_id = $row["id"];
			} else {
				// the compliance doesnt exist, add it, and use latest id
				$sql = "INSERT INTO Compliance (name) VALUES ('".$compliance."');";
				$result = $conn->query($sql);
				$compliance_id = $conn->insert_id;
			}

			// Tag this recipe with each compliance
			$sql    = "INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (".$recipe_id.", ".$compliance_id.");";
			$result = $conn->query($sql);
		}
	}

	// Handle Recipe Instructions
	$instructions = preg_split("/\r\n|\n|\r/", trim($_POST['instructions']));
	foreach( $instructions as $instruction )
	{
		$sql    = "INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (".$recipe_id.", '".$instruction."');";
		$result = $conn->query($sql);
	}

	// Handle Recipe Ingredients
	$ingredientLines = preg_split("/\r\n|\n|\r/", strtolower(trim($_POST['ingredients']))
	);
	foreach( $ingredientLines as $ingredientLine )
	{
		// Parse ingredient line
		$ingredientArr = [];
		$ingredientArr = preg_split('/,/', $ingredientLine);
		$amount        = trim($ingredientArr[0]);
		$measure       = trim($ingredientArr[1]);
		$ingredient    = trim($ingredientArr[2]);

		if (sizeof($ingredientArr) > 3) {
			$preparation = trim($ingredientArr[3]);
		} else {
			$preparation = " ";
		}

		$amountArr = preg_split('/[\s\/]+/', trim($amount), 3);

		if (sizeof($amountArr) == 3) {
			$amount_whole       = $amountArr[0];
			$amount_numerator   = $amountArr[1];
			$amount_denominator = $amountArr[2];
		} elseif (sizeof($amountArr) == 2) {
			$amount_whole       = 0;
			$amount_numerator   = $amountArr[0];
			$amount_denominator = $amountArr[1];
		} else {
			$amount_whole       = $amountArr[0];
			$amount_numerator   = 0;
			$amount_denominator = 0;
		}

		// See if ingredient exists
		$sql    = "SELECT * FROM Ingredient WHERE name = '".$ingredient."';";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// the ingredient exists, use this id
			$row = $result->fetch_assoc();
			$ingredient_id = $row["id"];
		} else {
			// the ingredient doesnt exist, add it, and use latest id
			$sql = "INSERT INTO Ingredient (name) VALUES ('".$ingredient."');";
			$result = $conn->query($sql);
			$ingredient_id = $conn->insert_id;
		}

		// See if measurement exists
		$sql    = "SELECT * FROM Measure WHERE name = '".$measure."';";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// the measurement exists, use this id
			$row = $result->fetch_assoc();
			$measure_id = $row["id"];
		} else {
			// the measurement doesnt exist, add it, and use latest id
			$sql = "INSERT INTO Measure (name) VALUES ('".$measure."');";
			$result = $conn->query($sql);
			$measure_id = $conn->insert_id;
		}

		// See if preparation exists
	//	if (sizeof($ingredientArr) > 3) {
			$sql    = "SELECT * FROM Preparation WHERE name = '".$preparation."';";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				// the preparation exists, use this id
				$row = $result->fetch_assoc();
				$preparation_id = $row["id"];
			} else {
				// the preparation doesnt exist, add it, and use latest id
				$sql = "INSERT INTO Preparation (name) VALUES ('".$preparation."');";
				$result = $conn->query($sql);
				$preparation_id = $conn->insert_id;
			}
	//	}

		// Associate ingredient with recipe
		$sql    = "INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount_whole, amount_numerator, amount_denominator, preparation_id)
	                   VALUES (".$recipe_id.", ".$ingredient_id.", ".$measure_id.", ".$amount_whole.", ".$amount_numerator.", ".$amount_denominator.", ".$preparation_id.");";
		$result = $conn->query($sql);

	}

	// Close connection
	$conn->close();
	
	header("Location: displayLarge.php?id=".$recipe_id);
    die();
}

?>

<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<meta name="viewport" content="width=device-width, initial-scale=0.95">
<title>Form site</title>
</head>
<body>
<form method="post" action="">

<?php

    // Create connection
    $conn = connect();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get Recipe Data
    $id           = $_GET['id'];
    $recipe       = query_recipe($conn, $id);
    $compliances  = query_recipe_compliances($conn, $id);
    $ingredients  = query_recipe_ingredients($conn, $id);
    $instructions = query_recipe_instructions($conn, $id);
    $log_entries  = query_recipe_log($conn, $id);
    
    // Create same fields that are in addRecipe.php but populate them with the Recipe.
    if ($recipe->num_rows > 0) {
        // recipe exists!
        $recipe = $recipe->fetch_assoc();

	    display_form_recipe_name($recipe[name]);
        display_form_recipe_description($recipe[description]);
        display_form_recipe_servings($recipe[servings]);
        display_form_recipe_prep_time($recipe[prep_time]);
        display_form_recipe_cook_time($recipe[cook_time]);
        display_form_recipe_hot_cold(strtolower($recipe[hot_cold]));
        display_form_recipe_meal_type(strtolower($recipe[meal_type]));
        
        $compliance_arr = [];
        if ($compliances->num_rows > 0) {
            while($row = $compliances->fetch_assoc()) {
                array_push($compliance_arr, $row[compliance]);
            }
        }
        display_form_recipe_compliances($compliance_arr);
        
        $ingredient_arr = [];
        if ($ingredients->num_rows > 0) {
            while($row = $ingredients->fetch_assoc()) {
                // Preparation string
                if (is_null($row[preparation])) {
                    $preparation_string = "";
                } elseif ($row[preparation] == " ") {
                    $preparation_string = "";
                } else {
                    $preparation_string = " - ".$row[preparation];
                }
                
                // Ingredient string
                if ( $row[amount_num] == 0 || $row[amount_den] == 0 ) {
                    $ingredient_string = $row[amount_whole] . ", " . $row[unit] . ", " . $row[ingredient] . $preparation_string;
                }
                elseif ( $row[amount_whole] == 0 ) {
                    $ingredient_string = $row[amount_num] . "/" . $row[amount_den]. ", " . $row[unit] . ", " . $row[ingredient] . $preparation_string;
                }
                else {
                    $ingredient_string = $row[amount_whole] . " " .$row[amount_num] . "/" . $row[amount_den]. ", " . $row[unit] . ", " . $row[ingredient] . $preparation_string;
                }
                
                array_push($ingredient_arr, $ingredient_string);
            }
        }
        display_form_recipe_ingredients($ingredient_arr);
        
        $instruction_arr = [];
        if ($instructions->num_rows > 0) {
            while($row = $instructions->fetch_assoc()) {
                array_push($instruction_arr, $row[instruction]);
            }
        }
        display_form_recipe_instructions($instruction_arr);
        
        $nutrition_arr = [];
        $nutrition_arr[0] = $recipe[calories];
        $nutrition_arr[1] = $recipe[total_fat];
        $nutrition_arr[2] = $recipe[saturated_fat];
        $nutrition_arr[3] = $recipe[cholesterol];
        $nutrition_arr[4] = $recipe[sodium];
        $nutrition_arr[5] = $recipe[carbohydrates];
        $nutrition_arr[6] = $recipe[fiber];
        $nutrition_arr[7] = $recipe[sugar];
        $nutrition_arr[8] = $recipe[protein];
        display_form_recipe_nutrition($nutrition_arr);
    }
	// Close connection
	$conn->close();
?>



	<button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
