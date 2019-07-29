<?php
// Start the session
session_start();
// Initialize session variables
if (!isset($_SESSION['gamer'])) {
$_SESSION['gamer'] = 1;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error']='';
}
if (!isset($_SESSION['duplicate_gamer'])) {
    $_SESSION['duplicate_gamer'] = 'no';
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
   
      // Check for duplicate gamer username
    if ($_SESSION['duplicate_gamer']=='yes'){
        // Send back to registration page
        header("Location: register.php");
        exit();
    } else {
    
    $statement = $db->prepare('INSERT INTO gamer (username, display_name, email, hashed_password) VALUES (:username, :display_name, :email, :hashed_password);');
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':display_name', $display_name, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':hashed_password', $hashed_password, PDO::PARAM_STR);
    $statement->execute();
    $_SESSION['gamer'] = $db->lastInsertId();
    $gamer = $_SESSION['gamer'];
    
    $statement2 = $db->prepare('INSERT INTO preference(gamer, preferences) 
                               VALUES (:gamer, :preferences);');
    $preferences = '{ "min_players":1, "max_players":1, "min_playtime":1, "max_playtime":15, "themes":[], "min_weight":1.0, "max_weight":1.5, "mechanisms":[]}';
    $statement2->bindValue(':gamer', $gamer, PDO::PARAM_INT);
    $statement2->bindValue(':preferences', $preferences, PDO::PARAM_STR);
    $statement2->execute();
    
     // Redirect to games page
    header("Location: games.php");
    exit();
    }
}

// If profile update submitted
if (isset($_POST['p_display_name'])){
    $display_name = htmlspecialchars($_POST['p_display_name']);    
    $email = htmlspecialchars($_POST['p_email']);
    $old_password = htmlspecialchars($_POST['p_old_password']);
    $new_password = htmlspecialchars($_POST['p_new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $gamer = (int)$_SESSION['gamer'];

    // Check for correct old password    
   $statement = $db->prepare("SELECT * FROM gamer WHERE gamer = $gamer");
    $statement->execute();
    $gamer_info = $statement->fetchAll(PDO::FETCH_ASSOC);
       
    $hashed_old_password = $gamer_info[0]['hashed_password'];
        if (password_verify($old_password, $hashed_old_password)) {
            // If password is correct, update user profile
            if (!empty($new_password)) { 
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
               $statement = $db->prepare('UPDATE gamer SET display_name = :display_name, email = :email, hashed_password = :hashed_password WHERE gamer = :gamer;');
                $statement->bindvalue(':gamer', $gamer, PDO::PARAM_INT);
                $statement->bindValue(':display_name', $display_name, PDO::PARAM_STR);
                $statement->bindValue(':email', $email, PDO::PARAM_STR);
                $statement ->bindValue(':hashed_password', $hashed_new_password, PDO::PARAM_STR);
           
            } else {
                $statement = $db->prepare('UPDATE gamer SET display_name = :display_name, email = :email WHERE gamer = :gamer;');
                $statement->bindvalue(':gamer', $gamer, PDO::PARAM_INT);
                $statement->bindValue(':display_name', $display_name, PDO::PARAM_STR);
                $statement->bindValue(':email', $email, PDO::PARAM_STR);
           
            }
            $statement->execute(); 
           // Redirect to games page
            header("Location: games.php");
            exit();   
           
            } else {
              // If password is incorrect, redirect to update page 
            $_SESSION['error'] = '<p>Old password incorrect.</p><br>';
            header("Location: edit_profile.php");
            exit();  
            }
    }   

// If login submitted
if (isset($_POST['l_username'])){
    $username = htmlspecialchars($_POST['l_username']); 
    $password = htmlspecialchars($_POST['l_password']);
    $statement = $db->prepare("SELECT * FROM gamer WHERE username = :username");
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    $gamer_info = $statement->fetchAll(PDO::FETCH_ASSOC);
    $gamer_name = $gamer_info[0]['username'];
                
// Check if username exists, if yes then verify password
    if (empty($gamer_name)) {
        // Redirect to login page
        $_SESSION['error'] = '<p>Username or password incorrect.</p><br>';
        header("Location: login.php");
        exit();
    } else {
        $hashed_password = $gamer_info[0]['hashed_password'];
        if(password_verify($password, $hashed_password)){
        // Update session variables
        $_SESSION["gamer"] = $gamer_info[0]['gamer'];
        // Redirect to games page
        header("Location: games.php");
        exit();
        } else {
            // Redirect to login page
            $_SESSION['error'] = '<p>Username or password incorrect.</p><br>';
            header("Location: login.php");
            exit();                    
            }   
        }   
    }


// if preferences submitted    
if (isset($_POST['go'])) {
    // Since these values are selected by dropdown and checkbox, no need for html sanitization
    $min_players = ($_POST['min_players']);    
    $max_players = ($_POST['max_players']);
    $min_playtime = ($_POST['min_playtime']);
    $max_playtime = ($_POST['max_playtime']);
    $min_weight = ($_POST['min_weight']);
    $max_weight = ($_POST['max_weight']);
    //  These two should be arrays, could be empty
    if (isset($_POST['themes'])) $themes = ($_POST['themes']);
        else $themes = [];
    if (isset($_POST['mechanisms'])) $mechanisms = ($_POST['mechanisms']);
        else $mechanisms = [];
    $gamer = (int)$_SESSION['gamer'];
   
     
    // create json preferences statement for UPDATE
    $prefs_json = '{ "min_players":' . $min_players . ', "max_players":' . $max_players . ', "min_playtime":' . $min_playtime . ', "max_playtime":' . $max_playtime . ',  "min_weight":' . $min_weight . ', "max_weight":' . $max_weight; 
    // still need to add themes and mechanisms
    $prefs_json = $prefs_json . ', "themes":[';
    $i = 0;
    foreach ($themes as $theme) {
        if(++$i === count($themes)) {
            $prefs_json = $prefs_json . '"' . "$theme" . '"';
        } else {
        $prefs_json = $prefs_json . '"' . "$theme" . '", ';
        }
    }
    $prefs_json = $prefs_json . '], "mechanisms":[';
    $i=0;
    foreach ($mechanisms as $mechanism) {
        if(++$i === count($mechanisms)) {
            $prefs_json = $prefs_json . '"' . "$mechanism" . '"';
        } else {
        $prefs_json = $prefs_json . '"' . "$mechanism" . '", ';
        }
    }
    $prefs_json = $prefs_json . ']}';
    // Update preferences
    $statement = $db->prepare("UPDATE preference SET preferences = '$prefs_json' WHERE gamer = $gamer");
    $statement->execute(); 
    
    // Now to get game recommendation from algorithm
    $recommended_game = '';
    $best_game_score = 0;
    $best_board_game = 0;  
    // Get board game data
    $statement2 = $db->prepare('SELECT * FROM board_game');
    $statement2->execute();
    $board_games = $statement2->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($board_games AS $board_game){   
        $game_score = 0;  // min 0 max 100
        // get game properties
        $properties = $board_game['properties'];   // this ends up as a string
        $properties_json = json_decode($properties);  // coerce to json object
        $game_min_players = $properties_json->min_players;
        $game_max_players = $properties_json->max_players;
        $game_min_playtime = $properties_json->min_playtime;
        $game_max_playtime = $properties_json->max_playtime;
        $game_weight = $properties_json->weight;
        $game_themes = $properties_json->themes;
        $game_mechanisms = $properties_json->mechanisms;
        
        // adjust game score for number of players
        if (!(($game_max_players < $min_players) OR ($game_min_players > $max_players))) {
            $game_score = $game_score + 20;
        }
        
        // adjust game score for playtime
        if (!(($game_max_playtime < $min_playtime) OR ($game_min_playtime > $max_playtime))) {
            $game_score = $game_score + 20;
        }
        
        // adjust game score for game weight
        if (($game_weight > $min_weight) AND ($game_weight < $max_weight)) {
            $game_score = $game_score + 20;
        }
        
        // adjust game score for theme
        $score_from_theme = 0;
        foreach ($themes as $theme) {
            foreach ($game_themes as $game_theme) {
                if ($theme == $game_theme) {
                    $score_from_theme = $score_from_theme + 5;
                }
            }
        }
        // max score for theme is 20
        if ($score_from_theme > 20) {
            $score_from_theme = 20;
        }
        $game_score = $game_score + $score_from_theme;
        
        // adjust game score for mechanisms
        $score_from_mechanisms = 0;
        foreach ($mechanisms as $mechanism) {
            foreach ($game_mechanisms as $game_mechanism) {
                if ($mechanism == $game_mechanism) {
                    $score_from_mechanisms = $score_from_mechanisms + 5;
                }
            }
        }
        // max score for mechanism is 20
        if ($score_from_mechanisms > 20) {
            $score_from_mechanisms = 20;
        }
        
        $game_score = $game_score + $score_from_mechanisms;   
        
        if ($game_score >= $best_game_score) {
            // check if this game has already been recommended to this gamer
            $already_recommended = 'false';
            $statement3 = $db->prepare("SELECT * FROM recommendation WHERE gamer = $gamer");
            $statement3->execute();
            $recommendations = $statement3->fetchAll(PDO::FETCH_ASSOC);
            foreach ($recommendations AS $recommendation) {
                if ($recommendation['board_game']==$board_game['board_game']) {
                    $already_recommended = 'true';
                }
            }
         echo $already_recommended;
            // if not, set this game to the best game 
            if ($already_recommended=='false'){
                $best_game_score = $game_score;
                $best_board_game = $board_game['board_game'];
            }
        }
    }
    
    // If not logged in as Guest, record final recommendation 
    if (($gamer!=1) AND ($best_board_game>0)) {
        $statement4 = $db->prepare("INSERT INTO recommendation (gamer, board_game) VALUES ($gamer, $best_board_game);");
        $statement4->execute();    
        }
    // redirect to recommendation page
      $_SESSION['best_game']= $best_board_game; 
      $_SESSION['best_game_score']=$best_game_score;
      header("Location: recommendation.php");
      exit(); 
    }
                
?>
