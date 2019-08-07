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
// Get the Heroku database
require_once "db_connect.php";
$db = get_db();
// Force display of all errors (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<!--
Homework Manager
Author: Kenneth Thomson
-->

<html lang="en-us">

<head>
    <?php $ROOT = '';
    include 'modules/head.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/register.js"></script>
    <title>Homework Manager</title>
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
</head>

<body>
    <header>
        <div id="header-band"></div>
        <div id="header-text" class="center-block">
            <h1>Homework Manager</h1>
        </div>
		<p id="author">By Kenneth Thomson</p>
    </header>
    <div class=center-block>
        <nav>
            <ul>
                <li><a href="index.php"><button type = "button">Tasks</button></a></li>
				<li><a href="login.php"><button type = "button">Login</button></a></li>
				<li><a href="register.php"><button type = "button">Register</button></a></li>
				<li><a href="edit_profile.php"><button type = "button" id = "active-nav">Profile</button></a></li>
                
            </ul>
        </nav>
        <main>
            <section class="wide-section">
                <?php
                    $query = 'SELECT * FROM student g WHERE g.student = ' . $_SESSION["student"];
                    $statement = $db->prepare($query);
                    $statement->execute();   
                    $student_data = $statement->fetch(PDO::FETCH_ASSOC);
                    // sanitize here for safe display
                    $display_name_safe = htmlspecialchars($student_data['display_name']);
                    $email_safe = htmlspecialchars($student_data['email']);
                
                // Redirect to login page if logged in as Guest
                   if ($student_data['student']==1) {
                    header("Location: login.php");
                    exit();
                   }
                ?>
                <br>
                <p>Here you may edit your user profile. Username cannot be changed.</p>
                <br>

                <form id="myForm" action="action_page.php" method="post">
                    <label for="display_name" class="label_long"><b>Display Name</b></label>
                    <input type="text" name="p_display_name" value="<?php echo $display_name_safe;  ?>" required /><br>
                    <label for="email" class="label_long"><b>Email</b></label>
                    <input type="email" name="p_email" value="<?php echo $email_safe;  ?>" required /><br>
                    <?php echo $_SESSION['error']; 
                $_SESSION['error'] = ''; ?>
                    <label for="old_password" class="label_long"><b>Old Password</b></label>
                    <input type="password" placeholder="Enter Old Password" name="p_old_password" required /><br>
                    <label for="new_password" class="label_long"><b>Old or New Password</b></label>
                    <input type="password" id="field_pwd1" placeholder="Re-enter Old Password or enter New Password (6+ chars, UPPER/lower + num)" title="Password must contain at least 6 characters, including UPPER/lowercase and numbers." name="p_new_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');" required /><br>
                    <button type="submit" class="submit_btn" id = "in_main" onclick="check_password()">UPDATE</button>
                </form>


            </section>


        </main>
        
    </div>
</body>

</html>
