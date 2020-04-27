<?php
    include 'util.php';

    // Create connection
    $conn = connect();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_GET['id'];

    echo $id;
 
    $conn->close();
?> 
