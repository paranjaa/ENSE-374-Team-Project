<?php 
    // Create conection to database
    $db = new mysqli("localhost", "natha20k", "Nalehk..", "natha20k");
    // Check connection
    if ($db->connect_error) {
        die("Connection error: " . $db->connect_error);
    }

    // SQL to create User table
    $sql = "CREATE TABLE ShareNoteUsers (
                user_id INT NOT NULL AUTO_INCREMENT,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(30) NOT NULL,
                DOB DATE NOT NULL,
                PRIMARY KEY (user_id)
            );";
    
    if($db->query($sql) === TRUE) {
        echo "ShareNoteUsers Table created successfully";
    }
    else {
        echo "Error creating user: " . $db->error;
    }

    $db->close();
?>