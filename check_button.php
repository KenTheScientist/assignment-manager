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

$sql = 'DELETE FROM task WHERE task = 8';
 
$stmt = $this->pdo->prepare($sql);
        
 
$stmt->execute();
 

header("Location: login.php");
exit();  


   
?>
