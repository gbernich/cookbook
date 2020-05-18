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

	// insert Recipe
	$total_time = $_POST['prep_time'] + $_POST['cook_time'];

	$sql = "INSERT INTO Recipe (name, description, servings, prep_time, cook_time, total_time, hot_cold,  meal_type)
				VALUES('".$_POST['name']."',
	                               '".$_POST['description']."',
	                                ".$_POST['servings'].",
	                                ".$_POST['prep_time'].",
	                                ".$_POST['cook_time'].",
	                                ".$total_time.",
	                               '".$_POST['hot_cold']."',
	                               '".$_POST['meal_type']."');";

	$result    = $conn->query($sql);
	$recipe_id = $conn->insert_id;

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
    $id = $_GET['id'];
    
    
    
    // Create same fields that are in addRecipe.php but populate them with the Recipe.
	display_form_recipe_name("test!!!");
    display_form_recipe_description("abcdefg");
    display_form_recipe_servings("4");
    display_form_recipe_prep_time("10");
    display_form_recipe_cook_time("15");
    display_form_recipe_hot_cold("cold");
    display_form_recipe_meal_type("lunch");
    display_form_recipe_compliances(array("whole30", "paleo"));
    display_form_recipe_ingredients(array("whole30", "paleo1"));
    display_form_recipe_instructions(array("whole30", "paleo2"));
    display_form_recipe_nutrition(array("1", "2", "3", "4", "5", "6", "7", "8", "9"));
    
	// Close connection
	$conn->close();
?>



	<button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
