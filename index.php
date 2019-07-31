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
				
				<li><a href="index.php"><button type = "button" id = "active-nav">Tasks</button></a></li>
				<li><a href="login.php"><button type = "button">Login</button></a></li>
				<li><a href="register.php"><button type = "button">Register</button></a></li>
				<li><a href="edit_profile.php"><button type = "button">Profile</button></a></li>
				
            </ul>
        </nav>
        <main>
			<p>
			<br>
				TASKS:
				<!--
			This is where we put tasks, input tasks, etc.
		   -->
			</p>
           

        </main>
        
    </div>
</body>

</html>
