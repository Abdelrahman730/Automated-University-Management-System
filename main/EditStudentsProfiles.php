<?php
    session_start();
    if ( !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
      header("location: ../login.php");
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- basic -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- mobile metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="initial-scale=1, maximum-scale=1">
  <!-- site metas -->
  <title>Power Campus</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- bootstrap css -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <!-- style css -->
  <link rel="stylesheet" href="../css/style.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

  <link rel="stylesheet" href="css/schedule.css">

  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<!-- body -->

<body class="main-layout contineer_page">
  <!-- header -->
  <header>
    <!-- header inner -->
 
      <div class="header">
        <div class="container">
          <div class="row">
            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-9">
              
               <div class="menu-area">
                <div class="limit-box">
                  <nav class="main-menu ">
                    <ul class="menu-area-main">
                      <li> <a href="index.php">Registeration</a> </li>
                  
                      <li> <a href="grades.php">Grades</a> </li>
                      <li> <a href="attendance.php">Attendance Report</a> </li>
                      <?php
                        if ( $_SESSION['loggedin']['Major'] == "Admin") {
                          echo '
                          <li class="active" >
                            <a href="#">Admin</a>
                            <ul>
                              <li><a href="RegisterUsers.php">Register Users</a></li>
                              <li><a href="EditSchedule.php">Edit Schedule</a></li>
                              <li><a href="EditStudentsProfiles.php">Edit Students profiles</a></li>
                            </ul>
                          </li>
                          ';
                        }
                      ?>
                      <li> <a href="../login.php">Logout</a> </li>
                      <li> <a href="#"><img src="../images/search_icon.png" alt="#" /></a> </li>
                      
                     </ul>
                   </nav>
                
               </div> 
             </div>
           </div>
         </div>
       </div>
     <!-- end header inner -->

     <!-- end header -->


</header>


	<div id="about" class="about">
		<div class="container">
      <div class="row">
        <div class="col-md-12">
          <form class="contact_bg" method = 'post'>
              <h1> Edit user profile</h1>
              <input class="contactus" placeholder="User email" type="text" name="userEmail" value = "<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : '' ?>">
              <button class="send" name="searchForUser" >Search for email</button>

              <?php
                $con = mysqli_connect("localhost", "root", "", "powercampus");
                if(isset($_POST['searchForUser'])){ 
                  $email = mysqli_real_escape_string($con,$_POST['userEmail']);
                    if ($email != ""){
                      $sql = "SELECT * FROM users WHERE Email='".$email."'";
                      $result = $con->query($sql);
                      if ( $result->num_rows == 1)
                      {
                        $Data = $result->fetch_assoc();
                        
                        echo '

                        <h1>User Data</h1>
                        <input class="contactus" value="'.$Data['Name'].'" type="text" name="userName">
                        <input class="contactus" value="'.$Data['Password'].'" type="text" name="userPassword">

                        <select class="contactus" name="userMajor">
                          <option value="Engineering"'. ($Data['Major'] == "Engineering" ? "selected='selected'" : '' ) .' >Engineering</option>
                          <option value="Computer Science"'. ($Data['Major'] == "Computer Science" ? "selected='selected'" : '' ) .' >Computer Science</option>
                          <option value="Business"'. ($Data['Major'] == "Business" ? "selected='selected'" : '' ) .' >Business</option>
                          <option value="Admin"'. ($Data['Major'] == "Admin" ? "selected='selected'" : '' ) .' >Admin</option>
                        </select>


                        <textarea class="contactus" name="userGrades" spellcheck="false" placeholder="Type grades here..." >'.$Data['Grades'].'</textarea>

                        <button class="send" name="updateUser" >Update Info</button>
                        ';
                      } else {
                        echo "<script> Swal.fire(
                          'Edit user profile',
                          'Account couldn't be found in database.',
                          'error'
                        )</script>";
                      }
                  }
                } elseif(isset($_POST['updateUser'])){
                  $email = mysqli_real_escape_string($con,$_POST['userEmail']);
                  $name = mysqli_real_escape_string($con,$_POST['userName']);
                  $password = mysqli_real_escape_string($con,$_POST['userPassword']);
                  $major = mysqli_real_escape_string($con,$_POST['userMajor']);
                  $grades = mysqli_real_escape_string($con,$_POST['userGrades']);
                  if ($email != "" && $name != "" && $password != ""){
                    $sql = "SELECT * FROM users WHERE Email='".$email."'";
                    $result = $con->query($sql);
                    
                    if ( $result->num_rows == 1)
                    {
                      $sql = "UPDATE users SET Name = '".$name."', Password = '".$password."', Major = '".$major."', Grades = '".$grades."' WHERE Email = '".$email."'";
                      $con->query($sql);

                      $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
                        "Attempt: has succesfully edit '".$email."' account data".PHP_EOL.
                        "User: ".json_encode($_SESSION['loggedin']['Email']).PHP_EOL.
                        "-------------------------".PHP_EOL;
                      file_put_contents('../log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);

                      echo "<script> Swal.fire(
                        'Edit user profile',
                        'User account info had been succesfuly edited.',
                        'success'
                      )</script>";
                    }
                  }
                }
              ?>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
          </form>
        </div>
			</div>
		</div>
	</div>
   
    <footr>
		<div class="footer ">
            <div class="container">
				<div class="copyright">  
					<p>Copyright 2021 All Right Reserved By <a href=""> Team Big</a></p>
				</div>
			</div>
		</div>
    </footr>

</body>
</html>