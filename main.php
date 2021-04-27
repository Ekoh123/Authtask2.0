<?php
include_once 'connection.php';
include 'query.php';

session_start();

if( $_SESSION['fname'] && $_SESSION['lname'] ){
$firstname = $_SESSION['fname'];
$lastname = $_SESSION['lname'];
$userid =  $_SESSION['userid'];

?> 

<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;   
    }
    .header{
        padding:2px;
        text-align: center;
    }
    .form{
        border: 1px solid blue;
        padding:5px;
        font-size:20px;
        text-align:center;
        margin: 5%;
        width: 20%;
        float:left;
    }
    table {
        border-collapse: collapse;
        width: 50%;
    }

    td, th {
        border-bottom: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    .top {
        background-color: blue;
        color: white;
        text-align: right;
        margin: 0;
        padding: 5px;
    }

    
</style>
<body>
    <div class="top">
        <form method="POST">
        <input type="submit" name="signout" value="SIGNOUT">
        <input type="submit" name="reset" value="RESET">
        </form>
    </div>
    <div>
        <div>
        <h1 style="text-align: center">WELCOME <?php echo strtoupper($firstname). ' '. strtoupper($lastname) ; ?> TO YOUR CLASSROOM </h1>
        <br>
        </div>
        <div class="form">
            <h2 style="text-align: center">Please choose a course</h2>
            <form style="text-align: center" method="POST">    
                <select name="course">  
                <option value = ""> Select Your Courses  </option>
                <?php
                    $connection = OpenCon();
                    $getCoursesQuery=getallcourses();
                    $coursesResult=QueryCon($connection, $getCoursesQuery); 
                    if ($coursesResult->num_rows > 0) {
                        while($row = $coursesResult->fetch_assoc()): 
                ?>
                        <option value = '<?php echo $row['coursename'] ?>'> <?php echo strtoupper($row['coursename'])  ?>  </option>
                    <?php 
                    endwhile; 
                    CloseCon($connection);
                }
                ?>
                </select>  
                <input type="submit" name="addcourse" value="ADD COURSE">
            </form>
         
       
        <h2 style="text-align: center">Did not find your course? Add one to the list</h2>
            <form style="text-align: center" method="POST">   
                <input type="text" id="coursearea" name="coursearea" placeholder="COURSE AREA"><br><br> 
                <input type="text" id="newcourse" name="newcourse" placeholder="COURSE NAME"><br><br>
                <input type="submit" name="sbmtnewcourse" value="ADD COURSE">
            </form>
        </div> 

        
        <?php
        ##########################################################################
        //adding course to database
        if(isset($_POST['addcourse'])){

                $course=trim(strtolower($_POST['course']));
                $message='';

                if(empty($course)) 
                {
                  $message = "<li>Please choose a course to add </li>";
                }
                if(!empty($message)) {
                    echo "<script type='text/javascript'>alert('<ul>$message<ul>');</script>";
                }else{
                    #################################################
                    //save course to the course table
                    $connection = OpenCon();
                    ###################################################
                    //check if course is chosen already by user
                    $sqlQuery= searchusercoursedata ($userid, $course);
                    $result=QueryCon($connection, $sqlQuery);                   
                    if ($result->num_rows > 0) {
                        $info = $result->fetch_assoc();
                        $count =  $info['ct'];                                                 
                    }

                    if ($count == 0) {
                        //if course is not chosen yet, add data to database
                       $addDataQuery=insertusercourse ($userid, $course);
                       $result=QueryCon($connection, $addDataQuery);
                       if ($result === TRUE) {
                        //if successful,
                       echo "<script type='text/javascript'> alert('Course added Successful, You can sign in now');</script>";
                       header("Location: main.php");  
                       exit;                      
                       }                        
                    }else {                       
                        echo "<script type='text/javascript'> alert('You have already added this course');</script>";          
                    }
                    CloseCon($connection);                                                                                                        
                    exit;   
                }
        }elseif(isset($_POST['sbmtnewcourse'])){

            $coursearea=trim(strtolower($_POST['coursearea']));
            $coursename=trim(strtolower($_POST['newcourse']));
            $message='';

            if(empty($coursearea)) 
            {
              $message .= "<li>Please choose a course to add </li>";
            }
            if(empty($coursename)) 
            {
              $message .= "<li>Please choose a course to add </li>";
            }
            if(!empty($message)) {
                echo "<script type='text/javascript'>alert('<ul>$message<ul>');</script>";
            }else{
                $connection = OpenCon();
                    ###################################################
                    //check if course is chosen already by user
                    $courseCountQuery= searchcoursecount ($coursearea ,$coursename);
                    $countResult=QueryCon($connection, $courseCountQuery);                   
                    if ($countResult->num_rows > 0) {
                        $ctinfo = $countResult->fetch_assoc();
                        $ct =  $ctinfo['ct']; 
                        print_r ($ct) ;                                               
                    }
                    if ($ct == 0) {
                        //if course is not chosen yet, add data to database
                       $insertNewCourseQuery=insertnewcourse ($coursearea ,$coursename);
                       $output=QueryCon($connection, $insertNewCourseQuery);
                       if ($output === TRUE) {
                        //if successful,
                       echo "<script type='text/javascript'> alert('Course added Successful, You can register for the course now');</script>";
                       header("Location: main.php");  
                       exit;                      
                       }                        
                    }else {                       
                        echo "<script type='text/javascript'> alert('This course already exists');</script>";          
                    }
                    CloseCon($connection);                                                                                                        
                    exit;   

            }
        }
        ?>
 
    </div>
    <div>
        <table>
            <thead style="font-weight: 700;">
                <tr>
                <td>Courses</td>
                <td>
                </td> 
                </tr>
            </thead>
            <?php 
            //get user's course data
            $connection = OpenCon();
            $sqlQuery=getusercourses ($userid);
            $result=QueryCon($connection, $sqlQuery); 
            $rows=$result->num_rows;
            if ($rows > 0) {
                while($row = $result->fetch_assoc()):        
            ?>                            
                    <tr>
                        <td><?php echo strtoupper($row['course'])  ?> </td>
                        <td>
                            <form method="POST">
                            <input type="submit" name="delete" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['cuid']; ?>"/>
                            </form>
                        </td>
                    </tr>
            <?php 
                endwhile; 
                CloseCon($connection);
            }

            #################################################
            #delete data from the database
            if(isset($_POST['delete'])){

                $cuid= $_POST['id'];               
                $connection = OpenCon();
                $sqlQuery=deletecourses ($userid, $cuid);
                $result=QueryCon($connection, $sqlQuery); 
                if ($result === TRUE) {
                    //if successful
                  // echo "<script type='text/javascript'> alert(Course has been deleted);</script>";
                   header("Location: main.php");  
                   exit;                      
               }                                    
            }

            ?>
        </table>
    </div>
    </body>
  

    <?php 

        if(isset($_POST['signout'])){
            session_unset();
            session_destroy();
            header("Location: landing.php");
        }elseif (isset($_POST['reset'])) {
            header("Location: reset.php");
        }
}else{
    header("Location: landing.php"); 
}
    ?>