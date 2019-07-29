<?php
// Start the session
session_start();
// Initialize session variables
if (!isset($_SESSION['gamer'])) {
    $_SESSION['gamer'] = 1;
}
if (!isset($_SESSION['best_game'])) {
    $_SESSION['best_game'] = 0;
}
if (!isset($_SESSION['best_game_score'])) {
    $_SESSION['best_game_score'] = 0;
}
// Get the Heroku database
require_once "db_connect.php";
$db = get_db();

?>

<!DOCTYPE html>
<!--
This is the login page for the Board Game Whisperer
Author: Nikkala Thomson
-->

<html lang="en-us">

<head>
    <?php $ROOT = '../';
    include '../modules/head.php'; ?>
    <title>The Board Game Whisperer</title>
</head>

<body>
    <header>
        <div id="header-band"></div>
        <div id="header-text" class="center-block">
            <h1>Your Game Recommendation</h1>
        </div>
    </header>
    <div class=center-block>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="edit_profile.php">Profile</a></li>
                <li><a href="games.php">Get Games</a></li>
            </ul>
        </nav>
        <main>


            <section class="wide-section">
                <br>
                <?php
                    $best_game = $_SESSION['best_game'];
                    $best_game_score = $_SESSION['best_game_score'];
               
                    // if there are no more games to recommend
                    if ($best_game==0) {
                        echo '<p>We are out of recommendations for you!  You have seen them all.  We hope you have enjoyed your time with The Board Game Whisperer.</p>';
                    }  else {
                        
                        $query = 'SELECT * FROM board_game b WHERE b.board_game = :board_game';
                        $statement = $db->prepare($query);
                        $statement->bindValue(':board_game', $best_game, PDO::PARAM_INT);
                        $statement->execute();   
                        $board_game = $statement->fetch(PDO::FETCH_ASSOC); 
                        $board_game_safe = htmlspecialchars($board_game['name']);
                        $game_image_safe = htmlspecialchars($board_game['image_url']);
                        echo '<p>The Board Game Whisperer thinks you have a ' . $best_game_score . '% chance of enjoying the game <h3>' . $board_game_safe . '.</h3></p><br><img src="' . $game_image_safe . '" alt="' . $board_game_safe . '">';
                        echo ' <br><br><form action="games.php" method="post">
                    <button type="submit" name="submit" class="submit_btn">Get Another Game Recommendation</button>
                </form>';
                    }?>


                <?php if($_SESSION['gamer']==1) : ?>
                <p>Note: You are logged in as "Guest." You will need to log in as a different user to get multiple recommendations.</p>
                <?php endif; ?>

            </section>
        </main>
        <footer>
            <?php include("../modules/footer.php"); ?>
        </footer>
    </div>
</body>

</html>
