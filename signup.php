<?php
include_once 'connection.php';
include 'query.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Zuri Assignment 2 webpage signup</title>
    <style>
    body{
        margin: 20%;
        margin-top: 3%;
    }
    .header{
        padding:2px;
        text-align: center;
    }
    .form{
        border: 1px solid #FB6600;
        padding:2px;
        font-size:20px;
        text-align:center;
        margin: auto;
        width:25%;
    }
    
</style>
</head>
<body>
    <!-- landing page of my site -->
    <div class="body">
        <div class="header">
            <h1> WELCOME TO MY WEBPAGE <h1>
            <h2> Please sign up so that you can sign-in later <h2>
        </div>

        <!-- form area -->
        <div class="form">
            <form method="POST">                                    
                <input type="text" id="fname" name="fname" placeholder="FIRST NAME"><br><br>
                <input type="text" id="lname" name="lname" placeholder="LAST NAME"><br><br>
                <input type="email" id="email" name="email" placeholder="test@email.com"><br><br>
                <input type="password" id="pword" name="pword" placeholder="PASSWORD"><br><br>
                <input type="submit" name="submit" value="submit">               
            </form>
        </div>

        <?php

                if(isset($_POST['submit'])){

                $firstname=strtolower($_POST['fname']);
                $lastname=strtolower($_POST['lname']);
                $email=strtolower($_POST['email']);
                $password=$_POST['pword'];
                $message='';


                if(empty($firstname)) 
                {
                  $message .= "<li>Please enter first name</li>";
                }
                if(empty($lastname)) 
                {
                  $message .= "<li>Please enter last name</li>";
                }
                if(empty($email)) 
                {
                  $message .= "<li>Please enter your email</li>";
                }
                if(empty($password)) 
                {
                  $message .= "<li>Please enter password</li>";
                }
              
                if(!empty($message)) 
                {
                    echo "<script type='text/javascript'>alert('$message');</script>";

                } else{
                               
                    //connect to database and search for unique email address.
                    #If address exit, notify user to give unique address               

                    $connection = OpenCon();
                    
                    //check if email exits
                    $sqlQuery= searchuseremail($email);
                    $result=QueryCon($connection, $sqlQuery);                   
                    if ($result->num_rows > 0) {
                        $info = $result->fetch_assoc();
                        $count =  $info['ct'];                                                 
                    }

                    if ($count == 0) {
                        //if email address is unique, add data to database
                       $addDataQuery=insertuserdata ($firstname, $lastname,$email, $password);
                       $result=QueryCon($connection, $addDataQuery);
                       if ($result === TRUE) {
                        //if successful
                       echo "<script type='text/javascript'> alert('Signup Successful, You can sign in now');</script>";
                       header("Location: landing.php");  
                       exit;                      
                       }                        
                    }else {                       
                        echo "<script type='text/javascript'> alert('Email should be unique');</script>";          
                    }
                    CloseCon($connection);                                                                                                        
                    exit;

                }              
            }
        ?> 
    </div>
</body>
</html>
