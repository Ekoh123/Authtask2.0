<?php

 //USER AREA  
    //for signup
     function insertuserdata ($firstname, $lastname,$email, $password){

        $sqlquery="INSERT INTO users (firstname, lastname, email, password )VALUES ('$firstname', '$lastname', '$email', '$password')";
        return $sqlquery;
    }

    // search a particular column for email
    function searchuseremail ($email){

        $sqlquery="SELECT count(*) as ct FROM users WHERE email = '$email'";
        return $sqlquery;
    }

    //  for the signin
     function getusercredentials ($email, $password){

        $sqlquery="SELECT count(*) as ct FROM users WHERE email = '$email' and password='$password'";
        return $sqlquery;
    }

    //  for getting userid
    function getuserid ($email, $password){

        $sqlquery="SELECT userid as id FROM users WHERE email = '$email' and password='$password'";
        return $sqlquery;
    }

    //for the reset password
     function updateuserpassword ($email, $password){

        $sqlquery="UPDATE users set password = '$password' where email = '$email'";
        return $sqlquery;
    }

// ##############################################################################################
//USERCOURSE AREA
    

    //for searches. search table for detail
    function searchusercoursedata ($userid, $course){

        $sqlquery="SELECT count(*) as ct FROM usercourses WHERE userid = $userid AND course = '$course'";
        return $sqlquery;
    }

    function searchcoursecount ( $coursearea ,$coursename){

        $sqlquery="SELECT count(*) as ct FROM course WHERE coursearea = '$coursearea' AND coursename = '$coursename'";
        return $sqlquery;
    }

    //get user's courses
    function getusercourses ($userid){

        $sqlquery="SELECT cuid, course FROM usercourses WHERE userid=$userid";
        return $sqlquery;
    }

    //get user's courses
    function getallcourses (){

        $sqlquery="SELECT coursename FROM course";
        return $sqlquery;
    }

    //for adding course
    function insertusercourse ($userid, $course){

        $sqlquery="INSERT INTO usercourses (userid, course )VALUES ('$userid', '$course')";
        return $sqlquery;
    }

    function updateusercourse ($edit, $cuid){

        $sqlquery="UPDATE usercourses set course = '$edit' where cuid = $cuid";
        return $sqlquery;
    }

    //Inserting a new course
    function insertnewcourse ($coursearea ,$coursename){

        $sqlquery="INSERT INTO course (coursearea ,coursename )VALUES ('$coursearea' ,'$coursename')";
        return $sqlquery;
    }
    ###############################################################
    //for searching email based on userid
    function getresetemail ($email){
        
        $sqlquery="SELECT count(*) as ct FROM users WHERE email = '$email'";
        return $sqlquery;
    }
    
    //delete user's courses
    function deletecourses ($userid, $cuid){

        $sqlquery="DELETE FROM usercourses WHERE userid=$userid AND cuid = $cuid";
        return $sqlquery;
    }
?>