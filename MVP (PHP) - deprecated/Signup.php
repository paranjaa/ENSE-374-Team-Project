<?php
$validate = true;
$error = "";
$reg_Email = "/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/";
$reg_Pswd = "/^(\S*)?\d+(\S*)?$/";
// $reg_Bday = "/^\d{1,2}\/\d{1,2}\/\d{4}$/";
$email = "";
// $date = "mm/dd/yyyy";

session_start();

if (isset($_SESSION["email"])){
    header("Location: index.php");
    $db->close();
    exit();
}
else {
    if (isset($_POST["submitted"]) && $_POST["submitted"]) {
        $email = trim($_POST["email"]);
        // $date = trim($_POST["date"]);
        $password = trim($_POST["password"]);
    
        $db = new mysqli("localhost", "natha20k", "Nalehk..", "natha20k");
        if ($db->connect_error) {
            die ("Connection failed: " . $db->connect_error);
        }
        
        $q1 = "SELECT * FROM ShareNoteUsers WHERE email = '$email'";
        $r1 = $db->query($q1);
    
        // if the email address is already taken.
        if($r1->num_rows > 0) {
            $validate = false;
    
        } else {
            $emailMatch = preg_match($reg_Email, $email);
            if($email == null || $email == "" || $emailMatch == false) {
                $validate = false;
            }
                  
            $pswdLen = strlen($password);
            $pswdMatch = preg_match($reg_Pswd, $password);
            if($password == null || $password == "" || $pswdLen < 8 || $pswdMatch == false) {
                $validate = false;
            }
    
            // $bdayMatch = preg_match($reg_Bday, $date);
            // if($date == null || $date == "" || $bdayMatch == false) {
            //     $validate = false;
            // }
        }
        
        if($validate == true) {
            // $dateFormat = date("Y-m-d", strtotime($date));

            //add code here to insert a record into the table ShareNoteUsers;
            // table ShareNoteUsers attributes are: email, password, DOB
            // variables in the form are: email, password, dateFormat, 
            // start with $q2 =
            // $q2 = "INSERT INTO ShareNoteUsers (email, password) VALUES ('$email', '$password', '$dateFormat')";

            //add code here to insert a record into the table ShareNoteUsers;
            // table ShareNoteUsers attributes are: email, password
            // variables in the form are: email, password
            $q2 = "INSERT INTO ShareNoteUsers (email, password) VALUES ('$email', '$password')";
            $r2 = $db->query($q2);
            
            if ($r2 === true) {
                header("Location: Login.php");
                $db->close();
                exit();
            }
    
        } else {
            $error = "Email address is not available. Signup failed.";
            $db->close();
        }
    
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <!-- <script type="text/javascript" src="Signup.js"></script> -->
</head>
<body>
    <h3>Sign up </h3>
    <form id="formSignup" action="Signup.php" method="post">
        <input type="hidden" name="submitted" value="1"/>
        <table>
            <tr>
            <td></td>
            <td></td>
            </tr>
            <tr>
            <td></td>
            <td id="email_S"></td>
            </tr>
            <tr>
            <td>Email</td>
            <td><input type="email" id="email" name="email" value=""/></td>
            </tr>
            <tr>
            <td></td>
            <td id="pswd_S"></td>
            </tr>
            <tr>
            <td>Password</td>
            <td><input type="password" id="password" name="password"/></td>
            </tr>

            <!-- <tr>
            <td></td>
            <td id="date_S"></td>
            </tr>
            <tr>
            <td>Birthday</td>
            <td><input type="text" id="date" name="date" value="mm/dd/yyyy"/></td>
            </tr> -->

            <tr>
            <td></td>
            <td><input type="submit" value="Sign up"/><input type="reset" value="Reset"/></td>

            </tr>
        </table>
    </form>

<script type="text/javascript" src="Signup.js"></script>    
<script type="text/javascript" src="SignupR.js"></script>
</body>
</html>
