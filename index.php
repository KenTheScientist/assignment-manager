<?php
// Start the session
session_start();
// Initialize session variables
if (!isset($_SESSION['student'])){
$_SESSION['student'] = 1;}

// Get the Heroku database
require_once "db_connect.php";
$db = get_db();
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
		<p id="author">By Kenneth Thomson</p>
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
				<?php
                    $query = 'SELECT * FROM student g WHERE g.student = ' . $_SESSION["student"];
                    $statement = $db->prepare($query);
                    $statement->execute();   
                    $student_data = $statement->fetch(PDO::FETCH_ASSOC);
                
                // Redirect to login page if logged in as Guest
                   if ($student_data['student']==1) {
                    header("Location: login.php");
                    exit();
                   }
                ?>
			<p>
			<br>
				<p> Welcome, <?php
                    $student = $_SESSION['student'];
                    $query = "SELECT display_name FROM student g WHERE g.student = $student";
                    $statement = $db->prepare($query);
                    $statement->execute();   
                    $student_data = $statement->fetch(PDO::FETCH_ASSOC);
                    echo $student_data['display_name'];
                ?>!</p>
				<br>
				Your Tasks:
				<br><br>
				<!--DISPLAY TASKS HERE-->
				<?php
				$student = $_SESSION['student'];
				$query = "SELECT * FROM task INNER JOIN assignment ON assignment.task = task.task AND assignment.student = $student";
				$statement = $db->prepare($query);
                $statement->execute();   
                $assignment_data = $statement->fetch(PDO::FETCH_BOTH);
				$result = pg_query($db,$query);
				echo "<table>";
				while($row=pg_fetch_assoc($result)){echo "<tr>";
				echo "<td align='center' width='200'>" . $row['task_name'] . "</td>";
				echo "<td align='center' width='200'>" . $row['due_date'] . "</td>";
				echo "<td align='center' width='200'>" . $row['task_class'] . "</td>";

				echo "</tr>";}echo "</table>";?>
				?>
				<br>
				<form id="myForm" action="action_page.php" method="post">
					Input Task: <input type="text" placeholder="NAME" name="r_name" required />
					<input type="date" name="r_date" value="today" min="2019-01-01" max="2022-01-01" required>
					<input type="text" placeholder="CLASS" name="r_class" required />
					<button type="submit" class="submit_btn" id = "plus_sign">+</button><br>
				</form>
			</p>
           

        </main>
        
    </div>
</body>

</html>
