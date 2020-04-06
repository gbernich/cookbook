<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
    <meta name="viewport" content="width=device-width, initial-scale=0.95">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
</head>

<body>

<?php
    include 'util.php';

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
    
    //display_recipe_header($recipe);    
    //display_recipe_compliances($compliances);
    //display_recipe_ingredients($ingredients);
    //display_recipe_instructions($instructions);
    //display_recipe_log($log_entries);

    // New Log Entry
    if(isset($_POST['submit']))
    {
    	$sql    = "INSERT INTO RecipeLog (recipe_id, cook_date, notes) VALUES (".$id.", NOW(),'".$_POST['notes']."');";
    	$result = $conn->query($sql);
    	header("Refresh:0");
    }

    $conn->close();
?>



  <div class="row">
    <div class="col">
      <?php display_recipe_header($recipe); ?>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <?php display_recipe_compliances($compliances); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-3">
      <?php display_recipe_ingredients($ingredients); ?>
    </div>
    <div class="col-6">
      <?php display_recipe_instructions($instructions); ?>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <?php display_recipe_log($log_entries); ?>
    </div>
  </div>



<br>
<h3>Log Entry</h3>

<form method="post" action="">
        <textarea cols="80" placeholder="Enter a note about your experience." rows="3" name="notes" maxlength="200" required></textarea><br>
        <button type="submit" name="submit">Put it in the books!</button>
</form>

</body>
</html>
