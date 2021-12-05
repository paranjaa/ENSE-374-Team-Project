<?php

$validate = true;
$reg_Email = "/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/";
$reg_Pswd = "/^(\S*)?\d+(\S*)?$/";

$email = "";
$error = "";

session_start();

if (isset($_SESSION["email"])){
    header("Location: index.php");
    $db->close();
    exit();
}
else{
    if (isset($_POST["submitted"]) && $_POST["submitted"]) {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        
        $db = new mysqli("localhost", "natha20k", "Nalehk..", "natha20k");
        if ($db->connect_error) {
            die ("Connection failed: " . $db->connect_error);
        }
    
        //add code here to select * from table ShareNoteUsers where email = '$email' AND password = '$password'
        // start with $q = 
    
        $q = "SELECT * FROM ShareNoteUsers WHERE email='$email' AND password='$password'";  
        $r = $db->query($q);
        $row = $r->fetch_assoc();
        if($email != $row["email"] || $password != $row["password"]) {
            $validate = false;
        } 
        else {
            $emailMatch = preg_match($reg_Email, $email);
            if($email == null || $email == "" || $emailMatch == false) {
                $validate = false;
            }
            
            $pswdLen = strlen($password);
            $passwordMatch = preg_match($reg_Pswd, $password);
            if($password == null || $password == "" || $pswdLen < 8 || $passwordMatch == false) {
                $validate = false;
            }
        }
        
        if($validate == true) {
            // session_start();  Commented out since session was started at the top to check if someone is already logged in
            $_SESSION["email"] = $row["email"];
            header("Location: fileViewer/fileViewer.php");
            $db->close();
            exit();
    
        } else {
            $error = "The email/password combination was incorrect. Login failed.";
            echo $error;
            $db->close();
        }
    }
}



?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<script type="text/javascript" src="Login.js"></script>
</head>
<body>
<h3>Login</h3>

<form id="formLogin" action="Login.php" method="post">
<input type="hidden" name="submitted" value="1"/>
<table>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td>Email</td>
<td>Password</td>
</tr>
<tr>
<td id="emailMsg"></td>
<td id="pswdMsg"></td>
</tr>
<td><input type="email" id="email" name="email" value=""/></td>
<td><input type="password" id="password" name="password"/></td>
<td><input type="submit" value="Login"/></td>
</tr>

<tr>
<td>No account? <a href="Signup.php">Sign up</a></td>
</tr>
</table>
</form>

<script type="text/javascript" src="LoginR.js"></script>
</body>
</html>
