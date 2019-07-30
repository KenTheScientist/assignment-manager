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
                <li id="active-nav"><a href="index.php"><button>Tasks</button></a></li>
                <li><a href="login.php"><button>Login</button></a></li>
                <li><a href="register.php"><button>Register</button></a></li>
                <li><a href="edit_profile.php"><button>Profile</button></a></li>
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
