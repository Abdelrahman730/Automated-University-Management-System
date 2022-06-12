<?php 
  session_start();
  $con = mysqli_connect("localhost", "root", "", "powercampus");

  function log_messages ($attempt,$user)
  {
    $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
      "Attempt: ".$attempt.PHP_EOL.
      "User: ".$user.PHP_EOL.
      "-------------------------".PHP_EOL;
    file_put_contents('log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
  }

  if(isset($_POST['registerSchedule'])){
    $sql = "UPDATE users SET Registered_Courses	 = '".$_POST['registerSchedule']."' WHERE Email = '".$_SESSION['loggedin']['Email']."'";
    $con->query($sql);
    $_SESSION['loggedin']['Registered_Courses'] = $_POST['registerSchedule'];

    foreach (json_decode($_POST['registerSchedule'],true) as $value)
    {
      $sql = "UPDATE coursesData SET Seats = Seats - 1 WHERE courseName = '".$value."'";
      $con->query($sql);
    }

    log_messages('has succesfully registered courses' , json_encode($_SESSION['loggedin']['Email']));
  }


  if(isset($_POST['dropSchedule'])){
    $sql = "UPDATE users SET Registered_Courses	 = '' WHERE Email = '".$_SESSION['loggedin']['Email']."'";
    $con->query($sql);
    

    foreach (json_decode($_SESSION['loggedin']['Registered_Courses'],true) as $value)
    {
      $sql = "UPDATE coursesData SET Seats = Seats + 1 WHERE courseName = '".$value."'";
      $con->query($sql);
    }

    $_SESSION['loggedin']['Registered_Courses'] = "";
    log_messages('has succesfully droped courses' , json_encode($_SESSION['loggedin']['Email']));
  }
?>