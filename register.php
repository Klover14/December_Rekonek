<?php

session_start();

$con = mysqli_connect("localhost", "root", "", "social_db");

if (mysqli_connect_errno()) {
    echo "Failed to connect:" . mysqli_connect_errno();
}

// Declaring varialbles to prevent errors
$fname = ""; //First Name
$lname = ""; //Last Name
$em = "";   //Email
$em2 = "";  //Email 2
$password = ""; //Password
$password2 = "";//Password 2
$date = "";     //Sign up Date
$error_array = array(); //Holds error messages

if(isset($_POST['register_button'])) {

    //Registration form values

    //First Name
    $fname = strip_tags($_POST['reg_fname']); //Remove HTML tags
    $fname = str_replace(' ', '', $fname);  //Removes spaces
    $fname = ucfirst(strtolower($fname));   //Uppercase first letter and the rest is lower cases
    $_SESSION['reg_fname'] = $fname;         //Stores first name into session variable

    //Last Name
    $lname = strip_tags($_POST['reg_lname']); //Remove HTML tags
    $lname = str_replace(' ', '', $lname);  //Removes spaces
    $lname = ucfirst(strtolower($lname));   //Uppercase first letter and the rest is lower cases
    $_SESSION['reg_lname'] = $lname;        //Stores last name into session variable



    //Email
    $em = strip_tags($_POST['reg_email']); //Remove HTML tags
    $em = str_replace(' ', '', $em);  //Removes spaces
    $em = ucfirst(strtolower($em));   //Uppercase first letter and the rest is lower cases
    $_SESSION['reg_email'] = $em;     //Stores email into session variable


    //Email 2
    $em2 = strip_tags($_POST['reg_email2']); //Remove HTML tags
    $em2 = str_replace(' ', '', $em2);  //Removes spaces
    $em2 = ucfirst(strtolower($em2));   //Uppercase first letter and the rest is lower cases
    $_SESSION['reg_email2'] = $em2;     //Stores confirm email into session variable


    //Password
    $password = strip_tags($_POST['reg_password']); //Remove HTML tags
    
    //Password 2
    $password2 = strip_tags($_POST['reg_password2']); //Remove HTML tags
    

    $date = date("Y-m-d");  //Current date


    if($em == $em2) {

        //Check if email have valid format
        if(filter_var($em, FILTER_VALIDATE_EMAIL)){

            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //Check if email already exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

            //Count the number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0) {
                array_push($error_array, "Email already in use <br>");
            }

        }else {
            array_push($error_array, "Invalid email format <br>");
        }

    }else {
        array_push($error_array, "Emails don't Match!");
    }


    if(strlen($fname) > 30 || strlen($fname) < 2) {
        array_push($error_array, "Your first name must be between 2 to 30 characters");
    }

    if(strlen($lname) > 30 || strlen($lname) < 2) {
        array_push($error_array, "Your last name must be between 2 to 30 characters");
    }

    if ($password != $password2) {
        array_push($error_array, "Your password do not match!");
    }
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)) {
           array_push($error_array, "Your password can only contain english characters or numbers");
        }
    }

    if(strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 to 30 characters");
    }



}



?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="register.php" method="POST">
        <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
        if(isset($_SESSION['reg_fname'])){
            echo $_SESSION['reg_fname'];
            }
            ?>" required>
        <input type="text" name="reg_lname" placeholder="Last Name"  value="<?php 
        if(isset($_SESSION['reg_lname'])){
            echo $_SESSION['reg_lname'];
            }
            ?>" required>
        <input type="email" name="reg_email" placeholder="Someone@gmail.com" value="<?php 
        if(isset($_SESSION['reg_email'])){
            echo $_SESSION['reg_email'];
            }
            ?>" required>
        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
        if(isset($_SESSION['reg_email2'])){
            echo $_SESSION['reg_email2'];
            }
            ?>" required>
        <input type="password" name="reg_password" placeholder="Password" required>
        <input type="password" name="reg_password2" placeholder="Confirm Password" required>
        <input type="submit"  name="register_button" value="Register">

    </form>

</body>
</html>