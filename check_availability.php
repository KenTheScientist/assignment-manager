<?php
// Start the session
session_start();
// Get the Heroku database
require_once 'db_connect.php';
$db = get_db();

if(!empty($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);
    $statement = $db->prepare('SELECT * FROM gamer WHERE username = :username');
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    $duplicate_gamer = $statement->fetchAll();
   
    if (!empty($duplicate_gamer)){
      echo "<span class='status-not-available'> Username $username is not available.  Please choose another username. </span>"; 
      $_SESSION["duplicate_gamer"]='yes';
    } else {
      echo "<span class='status-available'> Username Available. </span>";
      $_SESSION["duplicate_gamer"]='no';
    }
}
?>
