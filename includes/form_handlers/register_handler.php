<?php

// Declaring varialbles to prevent errors
$fname = ""; //First Name
$lname = ""; //Last Name
$em = "";   //Email
$em2 = "";  //Email 2
$password = ""; //Password
$password2 = ""; //Password 2
$date = "";     //Sign up Date
$error_array = array(); //Holds error messages

if (isset($_POST['register_button'])) {

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


    if ($em == $em2) {

        //Check if email have valid format
        if (filter_var($em, FILTER_VALIDATE_EMAIL)) {

            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //Check if email already exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

            //Count the number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if ($num_rows > 0) {
                array_push($error_array, "Email already in use<br>");
            }
        } else {
            array_push($error_array, "Invalid email format<br>");
        }
    } else {
        array_push($error_array, "Emails don't Match!<br>");
    }


    if (strlen($fname) > 30 || strlen($fname) < 2) {
        array_push($error_array, "Your first name must be between 2 to 30 characters<br>");
    }

    if (strlen($lname) > 30 || strlen($lname) < 2) {
        array_push($error_array, "Your last name must be between 2 to 30 characters<br>");
    }

    if ($password != $password2) {
        array_push($error_array, "Your password do not match!<br>");
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Your password can only contain english characters or numbers<br>");
        }
    }

    if (strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 to 30 characters<br>");
    }


    if (empty($error_array)) {

        $password = md5($password);     //Encrypt password before sending to database


        //Generate username by concatinating first name and last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

        $i = 0;
        //If username exists, add number to username
        while (mysqli_num_rows($check_username_query) != 0) {
            $i++;       //Add 1 to i
            $username = $username . "_" . $i;
            $check_username_query =  mysqli_query($con, "SELECT username FROM users username = '$username'");
        }

        //Profile Picture assignment
        $rand = rand(1, 2);     //Random number between 1 and

        if ($rand == 1)
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        elseif ($rand == 2)
            $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";



        $query = mysqli_query($con, "INSERT INTO users VALUES ('','$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

        array_push($error_array, "<span style='color: #14c800;'> You're all set! Go ahead and login! </span><br>");


        //Clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
}
