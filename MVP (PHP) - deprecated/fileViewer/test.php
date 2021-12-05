<?php 
    // // mkdir("userData/test");
    // // rmdir("userData/test");
    // // rm("text.txt");
    // // echo "What do you want to input? ";
    // // $input = rtrim(fgets(STDIN));
    // // echo "I got it:\n" . $input;
    // // echo $_SERVER['PHP_SELF'];
    // $currentLocation = $_SERVER['PHP_SELF'];
    // echo $currentLocation . "<br>";    //returns--> /~natha20k/ENSE374/fileViewer/test.php
    // echo basename($currentLocation) . "<br>";  //returns--> test.php
    // $folderLocation = chop($currentLocation, basename($currentLocation));
    // echo $folderLocation . "<br>";  //returns--> /~natha20k/ENSE374/fileViewer/
    // echo basename($folderLocation) . "/" . "<br>";
    $db = new mysqli("localhost", "natha20k", "Nalehk..", "natha20k");
    // Check connection
    if ($db->connect_error) {
        die("Connection error: " . $db->connect_error);
    }

    $sql = "SELECT * FROM ShareNoteFolders WHERE u_id='$user_id';";
    $repsonse = $db->query($sql);
    echo "<br>sql variable: " . print_r($sql) . "<br>";
    echo "<br>response variable: " . print_r($response) . "<br>";
    $responseArray = $response->fetch_array();
    echo "<br>response Array variable: " . $responseArray . "<br>";
    $size = count($responseArray["folderName"]);
    echo "The response array is: " . print_r($responseArray["folderName"]) . "<br>";
    echo "The response array size is: " . $size . "<br>";

    if (!$addFolderResponse) {
        echo "
            <span>Folder not added, it already exists.</span>
        ";
    }

    $db->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    
</body>
</html> -->