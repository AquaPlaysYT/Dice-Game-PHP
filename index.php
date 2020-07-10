<?php

include('database_connection.php');
session_start();

//If there is no auth var then redirect to login

if(!isset($_SESSION['IsAuthed']))
{
	header('location:login.php');
}

//This post function checks if the user has already been inserted into the database then sets the session vars

if(isset($_POST['StartGame']))
{

    //Defines user1 and 2's username from the post request

    $User1 = $_POST["User1"];
    $User2 = $_POST["User2"];

    //Selects from the users database the 2 usernames

    $query = "SELECT * FROM authedusers WHERE Username1 = '$User1' AND Username2 = '$User2'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();

    //If the count is smaller than 0 then we insert the user and define the sessions vars

    if($count > 0)
    {
        $result = $statement->fetchAll();
        foreach($result as $row)
        {

            //Here we define the sessions vars and then get a random number from 1-100000 and load the match

            $_SESSION['UserID'] = $row['ID'];
            $_SESSION['Username1'] = $row['Username1'];
            $_SESSION['Username2'] = $row['Username2'];

            $random_number = rand(1,100000);

            header("location: https://localhost/challenge/$random_number");
        }
    }
    else
    {

        //Here we insert the 2 users into the database and define the session vars

        $query1 = "INSERT INTO authedusers (Username1, Username2) VALUES ('$User1', '$User2')";
        $statement1 = $connect->prepare($query1);
        $statement1->execute();
        $result1 = $statement1->fetchAll();
        foreach($result1 as $row1)
        {

            //Here we define the sessions vars and then get a random number from 1-100000 and load the match

            $_SESSION['UserID'] = $row1['ID'];
            $_SESSION['Username1'] = $row1['Username1'];
            $_SESSION['Username2'] = $row1['Username2'];

            $random_number = rand(1,100000);

            header("location: https://localhost/challenge/$random_number");
        }
    }
    
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <link rel="stylesheet" href="style/main_dice.css">
    <link rel="stylesheet" href="style/now-ui-kit.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <title>Dice Game</title>
</head>
<style>
    
</style>
<body>

<center>
<div class="container p-3 my-3 bg-dark text-white">
<h1 class="title">Welcome to Dice Online</h1>
<p class="subtitle">Here you can challenge your friends at the ultimate dice battle!</p>
<form method="post">
	<div class="form-group">
		<label>First Username</label>
		<input type="text" name="User1" class="form-control" required />
	</div>
    <div class="form-group">
		<label>Second Username</label>
		<input type="text" name="User2" class="form-control" required />
	</div>
	<div class="form-group">
		<input type="submit" name="StartGame" class="btn btn-info" value="Start Game" />
        <a class="btn btn-info" href="https://localhost/logout.php" role="button">Logout</a>
	</div>
</form>
</div>

<h1 class="title">Recent Matches</h1>
<p class="subtitle">Here you can see the top 3 recent matches!</p>

<br>

<?php

//This is used to show 3 random recent matches

$query = "SELECT * FROM matches ORDER BY MatchID DESC LIMIT 3";
$statement = $connect->prepare($query);
$statement->execute();	
$result = $statement->fetchAll();
foreach($result as $row)
{

    //Here we define some vars using data from the database request

    $MatchID = $row['MatchID'];
    $Username1 = $row['Username1'];
    $Username2 = $row['Username2'];
    $User1Score = $row['User1Score'];
    $User2Score = $row['User2Score'];
    $WinnerUsername = $row['WinnerUsername'];


    //Since we are using a foreach loop this will echo (print the text) every time there is a row in the database

    echo '
    <div class="container p-3 my-3 bg-dark text-white">
        <h1 class="title">Match#'.$MatchID.'</h1>
        <h2>Match Winner: '.$WinnerUsername.'</h2>
        <hr style="height:2px;border-width:0;color:white;background-color:white">
        <h2>'.$Username1.': '.$User1Score.'</h2>
        <h2>'.$Username2.': '.$User2Score.'</h2>
        <hr style="height:2px;border-width:0;color:white;background-color:white">
        <br>
    </div>
    ';
}

?>

</center>

</body>
<html>