<?php
// Start the session
session_start();
// Initialize session variables
if (!isset($_SESSION['student'])) {
$_SESSION['student'] = 1;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error']='';
}
if (!isset($_SESSION['duplicate_student'])) {
    $_SESSION['duplicate_student'] = 'no';
}


// Get the Heroku database
require_once "db_connect.php";
$db = get_db();
// Force display of all errors (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// If registration submitted
if (isset($_POST['r_email'])){
    
   $username = htmlspecialchars($_POST['username']);
   $display_name = htmlspecialchars($_POST['r_display_name']);    
   $email = htmlspecialchars($_POST['r_email']);
   $password = htmlspecialchars($_POST['r_password']);
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
      // Check for duplicate student username
    if ($_SESSION['duplicate_student']=='yes'){
        // Send back to registration page
        header("Location: register.php");
        exit();
    } else {
    
    $statement = $db->prepare('INSERT INTO student (username, display_name, email, hashed_password) VALUES (:username, :display_name, :email, :hashed_password);');
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':display_name', $display_name, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':hashed_password', $hashed_password, PDO::PARAM_STR);
    $statement->execute();
    $_SESSION['student'] = $db->lastInsertId();
    $student = $_SESSION['student'];
    
     // Redirect to games page
    header("Location: index.php");
    exit();
    }
}

if (isset($_POST['r_name'])){
	$name = htmlspecialchars($_POST['r_name']);
	$class = htmlspecialchars($_POST['r_class']);
	$date = htmlspecialchars($_POST['r_date']);
	
	$statement = $db->prepare('INSERT INTO task (task_name, due_date, task_class) VALUES (:task_name, :due_date, :task_class);');
	$statement->bindValue(':task_name', $name, PDO::PARAM_STR);
	$statement->bindValue(':due_date', $date, PDO::PARAM_STR);
	$statement->bindValue(':task_class', $class, PDO::PARAM_STR);
	$statement->execute();
	
	// Insert student, task into assignment many many table
	$student = $_SESSION['student'];
	$task = $db->lastInsertId();
	$statement2 = $db->prepare('INSERT INTO assignment (student, task) VALUES (:student, :task);');
	$statement2->bindValue(':student', $student, PDO::PARAM_INT);
	$statement2->bindValue(':task', $task, PDO::PARAM_INT);
	$statement2->execute();
	
	header("Location: index.php");
    exit();
}

// If profile update submitted
if (isset($_POST['p_display_name'])){
    $display_name = htmlspecialchars($_POST['p_display_name']);    
    $email = htmlspecialchars($_POST['p_email']);
    $old_password = htmlspecialchars($_POST['p_old_password']);
    $new_password = htmlspecialchars($_POST['p_new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $student = (int)$_SESSION['student'];

    // Check for correct old password    
   $statement = $db->prepare("SELECT * FROM student WHERE student = $student");
    $statement->execute();
    $student_info = $statement->fetchAll(PDO::FETCH_ASSOC);
       
    $hashed_old_password = $student_info[0]['hashed_password'];
        if (password_verify($old_password, $hashed_old_password)) {
            // If password is correct, update user profile
            if (!empty($new_password)) { 
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
               $statement = $db->prepare('UPDATE student SET display_name = :display_name, email = :email, hashed_password = :hashed_password WHERE student = :student;');
                $statement->bindvalue(':student', $student, PDO::PARAM_INT);
                $statement->bindValue(':display_name', $display_name, PDO::PARAM_STR);
                $statement->bindValue(':email', $email, PDO::PARAM_STR);
                $statement ->bindValue(':hashed_password', $hashed_new_password, PDO::PARAM_STR);
           
            } else {
                $statement = $db->prepare('UPDATE student SET display_name = :display_name, email = :email WHERE student = :student;');
                $statement->bindvalue(':student', $student, PDO::PARAM_INT);
                $statement->bindValue(':display_name', $display_name, PDO::PARAM_STR);
                $statement->bindValue(':email', $email, PDO::PARAM_STR);
           
            }
            $statement->execute(); 
           // Redirect to games page
            header("Location: index.php");
            exit();   
           
            } else {
              // If password is incorrect, redirect to update page 
            $_SESSION['error'] = '<p>Old password incorrect.</p><br>';
            header("Location: index.php");
            exit();  
            }
    }   

// If login submitted
if (isset($_POST['l_username'])){
    $username = htmlspecialchars($_POST['l_username']); 
    $password = htmlspecialchars($_POST['l_password']);
    $statement = $db->prepare("SELECT * FROM student WHERE username = :username");
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    $student_info = $statement->fetchAll(PDO::FETCH_ASSOC);
    $student_name = $student_info[0]['username'];
                
// Check if username exists, if yes then verify password
    if (empty($student_name)) {
        // Redirect to login page
        $_SESSION['error'] = '<p>Username or password incorrect.</p><br>';
        header("Location: login.php");
        exit();
    } else {
        $hashed_password = $student_info[0]['hashed_password'];
        if(password_verify($password, $hashed_password)){
        // Update session variables
        $_SESSION["student"] = $student_info[0]['student'];
        // Redirect to games page
        header("Location: index.php");
        exit();
        } else {
            // Redirect to login page
            $_SESSION['error'] = '<p>Username or password incorrect.</p><br>';
            header("Location: login.php");
            exit();                    
            }   
        }   
    }
                
?>
