<?php 
  session_start();
  if ( !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header("location: ../login.php");
  }

  

  /*$var = [
    "2019 Fall"=> [
      "CSCI101"=> ["A+","Computer & Information",3],
      "CSCI201"=> ['A+','Introduction to programming',3],
      "MATH111"=> ['A+','Introduction to programming',3],
      "PHYS101C" => ['A+','Physics I',4],
      "MATH111" => ['A+','Analytical Geometry',4]
    ],
    "2020 Spring"=> [
      "CSCI205"=> ['A+','Introduction to Comp',3],
      "CSCI479"=> ['A+','Selected Topics in C',3],
      "ECEN101"=> ['A+','Electric Circuits',3],
      "ENGL-102" => ['A+','English II',4],
      "MATH112" => ['A+','Analytical Geometry',4]
    ],
    "2020 Fall"=> [
      "ARTS-201"=> ['A+','Introduction to Comp',3],
      "CSCI207"=> ['A+','Selected Topics in C',3],
      "ENGL201"=> ['A+','Electric Circuits',3],
      "MATH201" => ['A+','English II',4],
      "MATH211" => ['A+','Analytical Geometry',4],
      "PSCI-201" => ['A+','Analytical Geometry',4]
    ]
  ];*/

  //echo "<li>".$var['2019 Fall']['CSCI101'][1]."</li>";
  //echo json_encode($var)."\n";

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
                  
                      <li class="active" > <a href="grades.php">Grades</a> </li>
                      <li> <a href="attendance.php">Attendance Report</a> </li>
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
      <div class="row">
        <div class="col-md-12">
					
          <form class="contact_bg">
                  <?php
                    $GradeQuailty = [
                      "A+" => 4.0,
                      "A" => 4.0,
                      "A-" => 3.7,
                      "B+" => 3.3,
                      "B" => 3.0,
                      "B-" => 2.7,
                      "C+" => 2.3,
                      "C" => 2.0,
                      "C-" => 1.7,
                      "D+" => 1.3,
                      "D" => 1.0,
                      "F" => 0
                    ];
                    if ($_SESSION['loggedin']['Grades'] != "")
                    {
                    $arr = json_decode($_SESSION['loggedin']['Grades'],true);
                    $QualityPoint = 0;
                    $Credits = 0;
                    foreach($arr as $key=>$value )
                    {
                      echo "<h1> ".$key."</h1>";
                      echo "<table class='content-table' style='width: 100%' >";

                      echo "<colgroup>
                        <col span='1' style='width: 15%;'>
                        <col span='1' style='width: 35%;'>
                        <col span='1' style='width: 15%;'>
                        <col span='1' style='width: 15%;'>
                        <col span='1' style='width: 25%;'>
                        </colgroup>";

                      echo "<thead> <tr> <th>Course</th> <th>Title</th> <th>Grade</th> <th>Credits</th> <th>Quality Points</th> </tr> </thead>";

                      $CreditsCurrentTable = 0;
                      $QualityPointCurrentTable = 0;
                      foreach ($value as $key1=>$value1)
                      {
                        echo "<tr class ='active-row'> <tbody> 
                          <td>".$key1."</td> 
                          <td>".$value1[1]."</td>
                          <td>".$value1[0]."</td>
                          <td>".$value1[2]."</td>
                          <td>".($value1[2]*$GradeQuailty[$value1[0]])."</td>
                        </tbody>  </tr>";
                        

                        $CreditsCurrentTable += $value1[2];
                        $QualityPointCurrentTable += ($value1[2]*$GradeQuailty[$value1[0]]);
                      }
                      echo "<thead> <tr> <th>GPA: ".$QualityPointCurrentTable/$CreditsCurrentTable."</th> <th>-</th> <th>-</th> <th>".$CreditsCurrentTable."</th> <th>".$QualityPointCurrentTable."</th> </tr> </thead>";
                      echo "</table>";
                      
                      $QualityPoint += $QualityPointCurrentTable;
                      $Credits += $CreditsCurrentTable;
                    }
                    echo "<h1>GPA:".$QualityPoint/$Credits."</h1>";
                    echo "<h1>Credits:".$Credits."</h1>";
                    } else {
                      echo "<h1>No grades found.</h1>
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
                        <br>
                        <br>
                        <br>
                        <br>
                      ";
                    }
                  ?>
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