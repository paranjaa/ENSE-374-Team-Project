<?php
    session_start();
    // echo "<br> POST variable: ".print_r($_SERVER)."<br>";

    $db = new mysqli("localhost", "natha20k", "Nalehk..", "natha20k");
    // Check connection
    if ($db->connect_error) {
        die("Connection error: " . $db->connect_error);
    }

    // echo "<br>SESSION variable: " . print_r($_SESSION) . "<br>";

    if (isset($_POST["addNote"])){
        if ($_POST["note"]){
            $notesEntered = true;
        }
        else {
            $notesEntered = false;
        }
    }

    $email = $_SESSION["email"];
    echo "<br>Logged in as: " . $email . "<br>";

    if ($_POST["addNote"] && $notesEntered) {
        //Notes entered. Store in database
        // echo "<br>" . print_r($_SESSION) . "<br>";
        // $email = $_SESSION["email"];
        // echo "<br>Logged in as: " . $email . "<br>";



        $sql = "INSERT INTO ShareNoteNotes (folder_id, noteName, note, shareEnabled)
                        VALUES ($user_id, '$folderName', '$folderLocation');";



    }
    else if ($_POST["addNote"] && !$notesEntered){
        // echo "Note not saved. Cannot save a blank note.";
        // echo print_r($_POST);
    }




    $db->close();
?>

<!DOCTYPE html>
<html>
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
            <span>Option4</span>
            <span><a href='../Logout.php'>Logout</a></span>
        </div>
        <div class="fileEditor">
        <h2>Name of Note</h2>
            <!-- <p>The textarea element defines a multi-line input field.</p> -->

            <form id="createNote" action="fileEditor.php" method="post">
            <textarea name="note" rows="45" placeholder="Start writing your notes here..."></textarea>
            <br><br>
            <input type="submit" name="addNote" value="Save & Share"> 
            
            <?php
                if ($_POST["addNote"] && $notesEntered) {
                    echo "Note saved as 'NameOfNote'";
                    // echo print_r($_POST);
                    // INSERT INTO ShareNoteNotes (fields) VALUES (values);
                }
                else if ($_POST["addNote"] && !$notesEntered){
                    echo "Note not saved. Cannot save a blank note.";
                    // echo print_r($_POST);
                }
            ?>
            </form> 
        </div>
    </div>

</body>
</html>