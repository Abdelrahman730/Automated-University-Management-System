<?php 
/**
 * Database Operations - University Management System
 * 
 * This file handles database operations for course registration and dropping.
 * Uses prepared statements to prevent SQL injection attacks.
 */

session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'powercampus');

/**
 * Get database connection
 * @return mysqli Database connection object
 */
function getConnection() {
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$con) {
        error_log("Database connection failed: " . mysqli_connect_error());
        die("Database connection failed. Please try again later.");
    }
    
    return $con;
}

/**
 * Log user activities to file
 * 
 * @param string $attempt Description of the action attempted
 * @param string $user User identifier (email)
 */
function log_messages($attempt, $user) {
    $log = "IP: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
           "Attempt: " . $attempt . PHP_EOL .
           "User: " . $user . PHP_EOL .
           "-------------------------" . PHP_EOL;
    
    // Ensure log directory exists
    if (!file_exists('log')) {
        mkdir('log', 0755, true);
    }
    
    file_put_contents('log/log' . date("j.n.Y") . '.log', $log, FILE_APPEND);
}

/**
 * Register courses for a student
 * Uses prepared statements to prevent SQL injection
 */
if (isset($_POST['registerSchedule'])) {
    $con = getConnection();
    
    try {
        // Start transaction for data consistency
        mysqli_begin_transaction($con);
        
        $email = $_SESSION['loggedin']['Email'];
        $courses = $_POST['registerSchedule'];
        
        // Update user's registered courses using prepared statement
        $stmt = $con->prepare("UPDATE users SET Registered_Courses = ? WHERE Email = ?");
        $stmt->bind_param("ss", $courses, $email);
        $stmt->execute();
        $stmt->close();
        
        // Update session data
        $_SESSION['loggedin']['Registered_Courses'] = $courses;
        
        // Decrease seat count for each registered course
        $courseList = json_decode($courses, true);
        if (is_array($courseList)) {
            $stmt = $con->prepare("UPDATE coursesData SET Seats = Seats - 1 WHERE courseName = ? AND Seats > 0");
            
            foreach ($courseList as $courseName) {
                $stmt->bind_param("s", $courseName);
                $stmt->execute();
            }
            
            $stmt->close();
        }
        
        // Commit transaction
        mysqli_commit($con);
        
        // Log successful registration
        log_messages('Successfully registered courses', $email);
        
    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($con);
        error_log("Course registration error: " . $e->getMessage());
        log_messages('Failed to register courses: ' . $e->getMessage(), $email);
    }
    
    mysqli_close($con);
}

/**
 * Drop all registered courses for a student
 * Uses prepared statements to prevent SQL injection
 */
if (isset($_POST['dropSchedule'])) {
    $con = getConnection();
    
    try {
        // Start transaction for data consistency
        mysqli_begin_transaction($con);
        
        $email = $_SESSION['loggedin']['Email'];
        $currentCourses = $_SESSION['loggedin']['Registered_Courses'];
        
        // Clear user's registered courses using prepared statement
        $stmt = $con->prepare("UPDATE users SET Registered_Courses = '' WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
        
        // Increase seat count for each dropped course
        $courseList = json_decode($currentCourses, true);
        if (is_array($courseList)) {
            $stmt = $con->prepare("UPDATE coursesData SET Seats = Seats + 1 WHERE courseName = ?");
            
            foreach ($courseList as $courseName) {
                $stmt->bind_param("s", $courseName);
                $stmt->execute();
            }
            
            $stmt->close();
        }
        
        // Commit transaction
        mysqli_commit($con);
        
        // Update session data
        $_SESSION['loggedin']['Registered_Courses'] = "";
        
        // Log successful drop
        log_messages('Successfully dropped courses', $email);
        
    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($con);
        error_log("Course drop error: " . $e->getMessage());
        log_messages('Failed to drop courses: ' . $e->getMessage(), $email);
    }
    
    mysqli_close($con);
}
?>