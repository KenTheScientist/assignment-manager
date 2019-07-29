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
        <div id="header-band"></div>
        <div id="header-text" class="center-block">
            <h1>Homework Manager</h1>
        </div>
    </header>
    <div class=center-block>
        <nav>
            <ul>
                <li id="active-nav"><a href="index.php">Tasks<img src="../images/yellow-arrow.png" alt=""></a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="edit_profile.php">Profile</a></li>
            </ul>
        </nav>
        <main>
            <div class="flex">
                <section>
                    <p>Playing board games creates precious experiences and lasting memories. </p>
                    <br>
                    <p>Consider trying out a new board game today!</p>

                </section>
                <section>
                    <ul>
                        <li><a href="register.php">New? Register today.</a></li>
                        <li><a href="login.php">Been here before? Login.</a></li>
                        <li><a href="games.php">Get a game recommendation.</a></li>
                        <li><a href="edit_profile.php">Edit your user profile.</a></li>
                    </ul>
                </section>
            </div>

        </main>
        <footer>
            <?php include("modules/footer.php"); ?>
        </footer>
    </div>
</body>

</html>
