<?php 
    session_start();

    // echo "<br>" . print_r($_SESSION) . "<br>";

    // Below code creates table if it doesn't exist
    // Create connection to database
    $db = new mysqli("localhost", "natha20k", "Nalehk..", "natha20k");
    // Check connection
    if ($db->connect_error) {
        die("Connection error: " . $db->connect_error);
    }

    // Create Folders table if it doesn't exist
    $foldersExist = "describe ShareNoteFolders;";
    if (!$db->query($foldersExist)) {
        $sql = "CREATE TABLE ShareNoteFolders(
            folder_id INT NOT NULL AUTO_INCREMENT, 
            u_id INT NOT NULL, 
            folderName VARCHAR(30) NOT NULL,
            folderLocation VARCHAR(255) NOT NULL,
            PRIMARY KEY (folder_id),
            FOREIGN KEY (u_id) REFERENCES User(user_id)
        );";

        // if($db->query($sql) === TRUE) {
        //     echo "Folders table created successfully";
        // }
    }
    else {
        // $q = "SELECT * FROM User WHERE email='$email' AND password='$password'";  
        // $response = $db->error;
        // $contents = $response->fetch_assoc();
        // echo "Error creating Folders table: " . print_r($db->error) . "<br>";
    }


    //When add folder is clicked, check if it exists in database, if not create it and then display it. Do not allow blank folder names
    $name = $_POST["folderName"];
    // echo "The folder name is: " . $name . "<br>";
    if (isset($_POST["addFolder"])){
        if ($_POST["folderName"]){

            //search to see if it exists, if not add it and then display it
            // below line relies on $email variable being set from the $_SESSION SGvariable once the session is live
            $email = $_SESSION["email"];
            $sql = "SELECT user_id FROM ShareNoteUsers WHERE email='$email';";
            //here the assumption is a user_id is guarenteed to exist if a user is logged in based on previous data validation
            $response = $db->query($sql);
            $responseArray = $response->fetch_assoc();
            $user_id = $responseArray["user_id"];
            // echo "The user id retreived is: " . $user_id . "<br>";
            unset($sql);
            unset($response);
            unset($responseArray);
            $folderName = $_POST["folderName"];

            $sql = "SELECT * FROM ShareNoteFolders WHERE folderName='$folderName' AND u_id='$user_id';"; 
            // echo "<br>sql variable: " . print_r($sql) . "<br>";
            $response = $db->query($sql);
            $responseArray = $response->fetch_assoc();
            // echo "The response array folder name retreived is: " . $responseArray["folderName"] . "<br>";
            // echo "The response array is: " . print_r($responseArray) . "<br>";

            if ($responseArray["folderName"] == $folderName) {
                // caanot create folder because it already exists
                // echo "A folder with that name already exists";
                unset($sql);
                unset($response);
                unset($responseArray);
            }
            else {
                $currentLocation = $_SERVER['PHP_SELF'];
                $folderLocation = chop($currentLocation, basename($currentLocation));
                $folderLocation = basename($folderLocation) . "/" . $folderName . "/";
                $sql = "INSERT INTO ShareNoteFolders (u_id, folderName, folderLocation)
                        VALUES ($user_id, '$folderName', '$folderLocation');";
                // echo "<br>sql variable: " . print_r($sql) . "<br>";
                $addFolderResponse = $db->query($sql);
                // echo "<br>add folder response variable: " . print_r($addFolderResponse) . "<br>";
                if ($addFolderResponse === TRUE) {
                    // echo "It worked";
                    // $responseArray = $repsonse->fetch_assoc();
                    // echo "The folder was created, and the mysql repsonse was: " . print_r($responseArray) . "<br>";
                }
                else {
                    echo "Mysql Error";
                }
                
                
            }
            
            
        }
        // else {
        //     echo "<br>Folder not created, cannot have a balnk folder name. <br>";
        // }
    }
    


    //when add note is clicked, redirect to file editor and take needed values in $_POST
    // <input type="submit" name="addNote" value="Add Note">
    if (isset($_POST["addNote"])) {
        // $_SESSION['user_id'] = "batman";
        // $_SESSION['folderName'] = $folderName;
        // $_SESSION['folderLocation'] = "$folderLocation";

        // header("Location: ../fileEditor/fileEditor.php");
        // echo "<br><h1>folderName is: " . $folderName . "</h1><br>";
        header("Location: ../fileEditor/fileEditor.php?user_id=".$user_id."&folderName=".$folderName."&folderLocation=".$folderLocation);
        // header('Location: ../fileEditor/fileEditor.php?folderName='.$folderName);

        // header("Location: index.php?id=".$_POST['ac_id']."&err=".$login);
        
        // exit(); 
    }





    // $db->close();


    // // session_start();
    // echo "<br> POST: ";
    // $_POST["folderName"] = NULL;
    // $_POST["addFolder"] = NULL;
    // print_r($_POST);
    // echo "<br>";
    // // print_r($_SESSION);
    // echo "<br>";
    // echo (basename()) . "<br>";
    // echo basename(getcwd()) . "<br>";  //getcwd gets full path, basename just gets last directory
    // print_r($_POST["folderName"]);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>ShareNote - Home</title>
</head>
<body> 
    <div class="grid-container">
        <div class="ShareNoteHeader">
            <h1>ShareNote</h1>
        </div>
        <div class="navBar">
            <span>Option1</span>
            <span>Option2</span>
            <span>Option3</span>
            <span>
                <?php
                    $email = $_SESSION["email"];
                    echo "<br>Logged in as: " . $email . "<br>";
                ?>
            </span>
            <span><a href='../Logout.php'>Logout</a></span>
        </div>
        <div class="fileViewer">
            <div class="filepathBar"><span>clickable filepath bar - eg. /username/CS215/</span></div>
            <!-- <div class="actionButtonsBar">action buttons (download, upload, remove, etc) -->
            <div class="actionButtonsBar">
                <div class="create-folder"> 
                    <form id="createFolder" action="fileViewer.php" method="post">
                        <!-- <input type="hidden" name="submitted" value="1"/> -->
                        <input type="text" name="folderName" placeholder="Folder Name"><input type="submit" name="addFolder" value="Add Folder"> 
                        <?php 

                            $db = new mysqli("localhost", "natha20k", "Nalehk..", "natha20k");
                            // Check connection
                            if ($db->connect_error) {
                                die("Connection error: " . $db->connect_error);
                            }
                        
                            $sql = "SELECT * FROM ShareNoteFolders WHERE u_id='$user_id';";
                            // echo "<br> sql variable: " . print_r($sql) . "<br><br>";
                            $response = $db->query($sql);
                            // echo "<br> response variable: " . print_r($response) . "<br>";
                            // $size = $response['field_count'];
                            // echo "<br> response[num_rows] variable: " . print_r($response['field_count']) . "<br>";

                            // $responseArray = $response->fetch_assoc();
                            // echo "<br> response Array variable: " . print_r($responseArray) . "<br>";

                            // while($responseArray = $response->fetch_assoc()) {
                            //     // echo "<br> Hello <br>";
                            // }

                            // $size = count($responseArray["folderName"]);
                            // echo "<br> The response array is: " . print_r($responseArray) . "<br>";
                            // echo "<br> The response array size is: " . $size . "<br>";
                            // echo "<br>add folder response variable: " . print_r($addFolderResponse) . "<br>";

                            // if (!$addFolderResponse) {
                            //     echo "
                            //         <span>Folder not added, it already exists.</span>
                            //     ";
                            // }

                            if (isset($_POST["addFolder"])){
                                if (!$_POST["folderName"]){
                                        echo "<span>Folder not created, cannot have a blank folder name. </span>";
                                }
                                else if (!$addFolderResponse) {
                                        echo "
                                            <span>Folder not added, it already exists.</span>
                                        ";
                                }    
                                else {
                                    echo "<span> Folder: $folderName created. </span>";
                                }
                            };
                            
                            $db->close();
                        ?>
                    </form>
                    
                </div>
            </div>
            <div class="contentDisplay">
                <div class="previous-folder">Previous Folder</div>
                <div class="folder">
                    <!-- <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder1 -->
                    <?php 
                        if ($addFolderResponse === TRUE) {
                            // echo "It worked";
                            // $responseArray = $repsonse->fetch_assoc();
                            // echo "The folder was created, and the mysql repsonse was: " . print_r($responseArray) . "<br>";
                            // echo "folderName Variable: " . $folderName . "<br>";

                        
                            echo "
                                <div class='folder'>
                                <div><img class='folder-logo' src='assets/folder/Folder_icon.svg' alt='Logo' /></div>
                                $folderName
                                </div>
                            ";
                        }
                        else {
                            // echo "
                            //     <div class='folder'>
                            //     <div><img class='folder-logo' src='assets/folder/Folder_icon.svg' alt='Logo' /></div>
                            //     $folderName
                            //     </div>
                            // ";
                        }
                    ?>
                </div>
            
                <!-- <div class="folder">
                    <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder2
                </div>
                <div class="folder">
                    <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder3
                </div>
                <div class="file">
                    <div><img class="file-logo" src="assets/files/file_icon.svg" alt="Logo" /></div>
                    file1
                </div>
                <div class="folder">
                    <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder4
                </div>
                <div class="folder">
                    <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder5
                </div>
                <div class="folder">
                    <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder6
                </div>
                <div class="file">
                    <div><img class="file-logo" src="assets/files/file_icon.svg" alt="Logo" /></div>
                    file2
                </div>
                <div class="file">
                    <div><img class="file-logo" src="assets/files/file_icon.svg" alt="Logo" /></div>
                    file3
                </div>
                <div class="folder">
                    <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder8
                </div>
                <div class="folder">
                    <div><img class="folder-logo" src="assets/folder/Folder_icon.svg" alt="Logo" /></div>
                    folder9
                </div>
                <div class="file">
                    <div><img class="file-logo" src="assets/files/file_icon.svg" alt="Logo" /></div>
                    file4
                </div> -->
            </div>   
        </div>
        <div class="createNote">
            <form id="createFolder" action="fileViewer.php" method="post">
                <input type="submit" name="addNote" value="Add Note">
            </form>
        </div>

    </div>

    <script type="text/javascript" src="fileViewer.js"></script>
    <script type="text/javascript" src="fileViewer-r.js"></script>
</body>
</html>