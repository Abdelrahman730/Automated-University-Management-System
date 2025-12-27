<?php 
  session_start();
  $_SESSION['loggedin'] = false;
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
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- style css -->
  <link rel="stylesheet" href="css/style.css">
  
  
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
                      <li> <a href="index.html">Home</a> </li>
                  
                      <li> <a href="about.html">About</a> </li>
                      <li> <a href="https://courses.nu.edu.eg/login/index.php">Moodle </a> </li>
                      <li> <a href="https://nileuniversity.sharepoint.com/Sites/portal/">Portal</a> </li>
                      <li class="active"> <a href="login.php">login</a> </li>
                      <li> <a href="#"><img src="images/search_icon.png" alt="#" /></a> </li>
                      
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

<div class="backgro_mh">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="heding">
           <h2>Login</h2>
        </div>
      </div>
    </div>
  </div>
</div>


   
    <footr>
      <div class="footer ">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
			
			
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			
            <form class="contact_bg" method = "post">
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-12">
                    <input class="contactus" placeholder="Email" type="text" name="Email">
                  </div>
                  <div class="col-md-12">
                    <input class="contactus" placeholder="Password" type="password" name="Password">
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <button class="send" name="login_submit" >Login</button>
                  </div>
                  <?php
                  if(isset($_POST['login_submit'])){
                      $con = mysqli_connect("localhost", "root", "", "powercampus");
                      
                      $uname = mysqli_real_escape_string($con,$_POST['Email']);
                      $password = mysqli_real_escape_string($con,$_POST['Password']);

                      if ($uname != "" && $password != ""){
                          $sql = "SELECT * FROM users WHERE Email='".$uname."' AND Password='".$password."'";
                          $result = $con->query($sql);
                          
                          if ( $result->num_rows == 1)
                          {
                              header("location: main/index.php");
                              session_start();
                              $_SESSION['loggedin'] = $result->fetch_assoc();

                              // Log

                              $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
                                "Attempt: has succesfully loged in".PHP_EOL.
                                "User: ".$uname.PHP_EOL.
                                "-------------------------".PHP_EOL;
                              file_put_contents('log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
                          } else {
                              header("location: login.php");

                              $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
                                "Attempt: entered invalid email or password".PHP_EOL.
                                "User: ".$uname.PHP_EOL.
                                "-------------------------".PHP_EOL;
                              file_put_contents('log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
                          } 
                      } else {
                          header("location: login.php");
                      }
                      exit();
                  }
                ?>
                </div>
              </div>
            </form>
			
			
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>

            </div>
            
            

                </div>

              </div>
               <div class="container">
              <div class="copyright">
               
                  <p>Copyright 2021 All Right Reserved By <a href=""> Team Big</a></p>
                </div>
              </div>
            </div>
          </footr>

</body>

</html>