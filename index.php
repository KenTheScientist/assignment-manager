<?php
// Start the session
session_start();
// Initialize session variables
if (!isset($_SESSION['student'])){
$_SESSION['student'] = 1;}


?>
<!DOCTYPE html>
<!--
This is the best page ever for homework management
Author: Ken
-->

<html lang="en-us">

<head>
    <?php $ROOT = '';
    include 'modules/head.php'; ?>
    <title>Homework Manager</title>
</head>

<body>
    <header>
        <div id="header-text" class="center-block">
            <h1>Homework Manager</h1>
        </div>
    </header>
    <div class=center-block>
        <nav>
            <ul>
                <!--<li id="active-nav"><a href="index.php">Tasks</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="edit_profile.php">Profile</a></li>-->
				
				<li><form action= "index.php"><button type = "button" >Tasks</button></form></li>
				<li><form action= "login.php"><button type = "button" >Login</button></form></li>
				<li><form action= "register.php"><button type = "button" >Register</button></form></li>
				<li><form action= "edit_profile.php"><button type = "button" >Profile</button></form></li>
				
            </ul>
        </nav>
        <main>
        
           

        </main>
        <footer>
            <?php include("modules/footer.php"); ?>
        </footer>
    </div>
</body>

</html>
