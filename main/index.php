<?php 
  session_start();
  if ( !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header("location: ../login.php");
  }

  // Conver json file to mysql datas
  /*$con = mysqli_connect("localhost", "root", "", "powercampus");
  $jsondata = file_get_contents('../schedule/schedule.json');
  $data = json_decode($jsondata, true);

  foreach($data as $courseCode=>$courseValues )
  {
    $sql = "INSERT INTO courses (ID, CourseName, Credit, Tutorial , Lab , prerequisites)
    VALUES ('".$courseCode."', '".$data[$courseCode]['CourseName']."', '".$data[$courseCode]['Credits']."', '".($data[$courseCode]['Tutorial'] ? 1 : 0 )."', '".($data[$courseCode]['Lab'] ? 1 : 0)."', '". json_encode($data[$courseCode]['prerequisites'])."')";
    $con->query($sql);
    foreach($data[$courseCode]['CourseData'] as $Key=>$Value )
    {
      $i = 0;
      foreach($data[$courseCode]['CourseData'][$Key] as $_ )
      {
        $courseData = $data[$courseCode]['CourseData'][$Key][$i];
        
        $courseDay = $courseData['Day'];
        $courseStart = $courseData['Start'];
        $courseEnd = $courseData['End'];
        $courseInsturctor = $courseData['Insturctor'];
        $courseSeats = $courseData['Seats'];

        $courseName = $courseCode . "-" . ($Key == 'Lecture' ? "Lec" : ($Key == 'Lab' ? $Key : "TUT" )) . "-" . ($i + 1) ;

        $sql2 = "INSERT INTO coursesData (courseID, courseName, Type, Insturctor , Seats , Day , Start , End)
        VALUES ('".$courseCode."', '".$courseName."', '".$Key."', '".$courseInsturctor."', '".$courseSeats."' , '".$courseDay."' , '".$courseStart."' , '".$courseEnd."')";

        $con->query($sql2);
        $i++;
      }
    }
  }*/
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
  

  <link rel="stylesheet" href="css/schedule.css">
  <link rel="stylesheet" href="css/search.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    var coursesData = <?php 
      $courseData = [];
      $con = mysqli_connect("localhost", "root", "", "powercampus");
      $result = $con->query("SELECT * FROM courses");
      while($row = $result->fetch_assoc()) {
        $courseData[$row['ID']] = array(
          "CourseName" => $row['CourseName'],
          "Credits" => (int)$row['Credit'],
          "Tutorial" => ($row['Tutorial'] == "1" ? true : false),
          "Lab" => ($row['Lab'] == "1" ? true : false),
          "prerequisites" =>  json_decode($row['prerequisites']),
          "CourseData" => []
        );
        $keys = array('Lecture');
          if ($row['Tutorial'])
          {
            array_push($keys,'Tutorial');
          }
          if ($row['Lab'])
          {
            array_push($keys,'Lab');
          }
          $courseData[$row['ID']]['CourseData'] = array_fill_keys($keys, array());
      }
    
      $result = $con->query("SELECT * FROM coursesData");
      while($row = $result->fetch_assoc()) {
        array_push($courseData[$row['courseID']]['CourseData'][$row['Type']], array( "Day" => (int)$row['Day'] , "Start" => (int)$row['Start'] ,
         "End" => (int)$row['End'] , "Insturctor" => (int)$row['Insturctor'] , "Seats" => (int)$row['Seats'] ));
      }
    
      echo json_encode($courseData)
    ?>;

    var convertedSchedule = <?php
      echo json_encode($_SESSION['loggedin']['Registered_Courses']);
    ?>;

    var userGrades = <?php
      echo json_encode($_SESSION['loggedin']['Grades']);
    ?>;


  </script>


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
                      <li class="active"> <a href="index.php">Registeration</a> </li>
                  
                      <li> <a href="grades.php">Grades</a> </li>
                      <li> <a href="attendance.php">Attendance Report </a> </li>
                      <?php
                        if ( $_SESSION['loggedin']['Major'] == "Admin") {
                          echo '
                          <li>
                            <a href="#">Admin</a>
                            <ul>
                              <li><a href="registerUsers.php">Register Users</a></li>
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
			<div class="row display_boxflex">
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
					<div class="about-box">
						<h2>Registeration</h2>
						<div class="wrapper">
							<div class="search-input">
								<a href="" target="_blank" hidden></a>
								<input type="text" id="SearchBox" placeholder="Type to search..">
								<div class="autocom-box"></div>
							</div>
						</div>
						<br>
						<a id = "addCourseButton" >Add Course</a>
						
						
						<br>
						<br>
						<br>
						
						
						<ul class="todoList"></ul>
						
						<br>
						<a2 id = "GenerateSchedules" >Generate Schedules</a2>
						
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
					<div class="about-box">
						<div id="table"></div>	
						<a id = "NextTable" >Next</a>
						<a1 id = "PrevTable">Previous</a1>
						<h3 id = "SchedulesIndex"></h3>
            <br>
            <br>

            <form class="contact_bg">
              <button class="send" id ="dropSchedule" >Drop Schedule</button>
              <button class="send" id ="registerSchedule" >Register Schedule</button>
            </form>

					</div>
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

	<script type="text/javascript" src="js/coursesList.js"></script>
</body>

</html>