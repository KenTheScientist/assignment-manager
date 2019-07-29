<?php
// Start the session
session_start();
// Initialize session variables
if (!isset($_SESSION['gamer'])){
$_SESSION['gamer'] = 1;
}
if (!isset($_SESSION['duplicate_gamer'])) {
    $_SESSION['duplicate_gamer'] = 'no';
}
// Get the Heroku database
require_once "db_connect.php";
$db = get_db();
?>
<!DOCTYPE html>
<!--
This is the registration page for the Board Game Whisperer
Author: Nikkala Thomson
-->

<html lang="en-us">

<head>
    <?php $ROOT = '../';
    include '../modules/head.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/register.js"></script>
    <title>The Board Game Whisperer | Register</title>
</head>

<body>
    <header>
        <div id="header-band"></div>
        <div id="header-text" class="center-block">
            <h1>New Account Registration</h1>
        </div>
    </header>
    <div class=center-block>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li id="active-nav"><a href="register.php">Register<img src="../images/yellow-arrow.png" alt=""></a></li>
                <li><a href="edit_profile.php">Profile</a></li>
                <li><a href="games.php">Get Games</a></li>
            </ul>
        </nav>
        <main>
            <section class="wide-section">
                <form id="myForm" action="action_page.php" method="post">
                    <br>
                    <label for="username" class="label_long"><b>Username</b></label>
                    <input id="field_username" type="text" title="Username must not be blank and contain only letters, numbers and underscores." placeholder="Enter Username" name="username" onBlur="checkAvailability()" required pattern="\w+" /><span id="user-availability-status"></span>

                    <br><br>
                    <label for="display_name" class="label_long"><b>Display Name</b></label>
                    <input type="text" placeholder="Enter Display Name" name="r_display_name" required /><br><br>
                    <label for="email" class="label_long"><b>Email</b></label>
                    <input type="email" placeholder="Enter Email" name="r_email" required /><br><br>
                    <label for="password" class="label_long"><b>Password</b></label>
                    <input type="password" id="field_pwd1" title="Password must contain at least 6 characters, including UPPER/lowercase and numbers." placeholder="Enter Password (6+ characters including UPPER/lowercase and number)" name="r_password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');
  if(this.checkValidity()) form.pwd2.pattern = RegExp.escape(this.value);" /><br><br>
                    <label for="password2" class="label_long"><b>Repeat Password</b></label>
                    <input type="password" id="field_pwd2" title="Please enter the same password as above" placeholder="Confirm Password" name="r_password2" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" name="pwd2" onchange="
  this.setCustomValidity(this.validity.patternMismatch ? this.title : '');" /><br>
                    <button type="submit" class="submit_btn" onclick="check_password()">REGISTER</button><br>
                    <p>Already have an account? <a href="login.php">Log in</a>.</p><br>
                </form>
            </section>
        </main>

        <footer>
            <?php include("../modules/footer.php"); ?>
        </footer>
    </div>
</body>

</html>
