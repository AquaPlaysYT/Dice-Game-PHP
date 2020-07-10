<?php

include("../database_connection.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);

//Defines all post, sessions and page vars

$viewResults = $_REQUEST['view'];
$matchID = $_REQUEST['id'];
$Username1 = $_SESSION['Username1'];
$Username2 = $_SESSION['Username2'];

$roll1 = "unkown";
$roll2 = "unkown";
$roll3 = "unkown";
$roll4 = "unkown";
$output_info = "";
$output_info1 = "";
$player1_score = 0;
$player2_score = 0;

//Checks if we our authed or redirects

if(!isset($_SESSION['IsAuthed']))
{
	header('location:login.php');
}

//This system is not the best however if the url contains /view at the end of our match it will display the results for that ID

if($viewResults > 0)
{

  //This does the same as the other file

  if(isset($_POST['StartGame']))
  {
    $User1 = $_POST["User1"];
    $User2 = $_POST["User2"];

    $query = "SELECT * FROM authedusers WHERE Username1 = '$User1' AND Username2 = '$User2'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();
    if($count > 0)
    {
        $result = $statement->fetchAll();
        foreach($result as $row)
        {
            $_SESSION['UserID'] = $row['ID'];
            $_SESSION['Username1'] = $row['Username1'];
            $_SESSION['Username2'] = $row['Username2'];

            $random_number = rand(1,100000);

            header("location: https://localhost/challenge/$random_number");
        }
    }
    else
    {
      $query1 = "INSERT INTO authedusers (Username1, Username2) VALUES ('$User1', '$User2')";
      $statement1 = $connect->prepare($query1);
      $statement1->execute();
      $result1 = $statement1->fetchAll();
      foreach($result1 as $row1)
      {
        $_SESSION['UserID'] = $row1['ID'];
        $_SESSION['Username1'] = $row1['Username1'];
        $_SESSION['Username2'] = $row1['Username2'];

        $random_number = rand(1,100000);

        header("location: https://localhost/challenge/$random_number");
      }
    }  
  }


  //This sets the rounds var to 0 and then generates a random number between 1-100000

  $_SESSION['rounds'] = 0;
  $random_number = rand(1,100000);

  //This will execute an sql request and grab all the match data

  $query = "SELECT * FROM matches WHERE MatchID = $viewResults";
  $statement = $connect->prepare($query);
  $statement->execute();	
  $count = $statement->rowCount();

  //If the count is smaller than 0, that means there was no results found witch will output a string "No data found!"

  if($count > 0)
  {
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
      $total_user1 = $row['User1Score'];
      $total_user2 = $row['User2Score'];
      $winner = $row['WinnerUsername'];

      //Here we echo the full Html code using the vars that are defined above

      echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://localhost/style/bootstrap.min.css">
            <link rel="stylesheet" href="https://localhost/style/main_dice.css">
            <link rel="stylesheet" href="https://localhost/style/now-ui-kit.css">
            <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
            <title>Dice Game</title>
        </head>
        <body>
        
        <center>
        
        <div class="container p-3 my-3 bg-dark text-white">
          <br>
          <h2>Game Results#'.$viewResults.'</h2>

          <hr style="height:2px;border-width:0;color:white;background-color:white">

          <h2>Winner: '.$winner.'</h2>
          <h2>'.$Username1.': '.$total_user1.'</h2>
          <h2>'.$Username2.': '.$total_user2.'</h2>

          <hr style="height:2px;border-width:0;color:white;background-color:white">

          <input type="submit" name="StartGame" class="btn btn-info" value="New Game" />
          <a class="btn btn-info" href="https://localhost/index.php" role="button">Return Home</a>
        </div>
        
        </center>
        
        </body>
        <html>
        ';

    }
  }
  else
  {


    //If there is no username or username2 then the game will end

    if(!isset($Username1))
    {
      header('location:index.php');
    }
    else if(!isset($Username2))
    {
      header('location:index.php');
    }

    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://localhost/style/bootstrap.min.css">
        <link rel="stylesheet" href="https://localhost/style/main_dice.css">
        <link rel="stylesheet" href="https://localhost/style/now-ui-kit.css">
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
        <title>Dice Game</title>
    </head>
    <body>
    
    <center>
    
    <div class="container p-3 my-3 bg-dark text-white">
      <br>
      <h2>Game Results#'.$viewResults.'</h2>

      <hr style="height:2px;border-width:0;color:white;background-color:white">

      <h2>This MatchID has no results!</h2>

      <hr style="height:2px;border-width:0;color:white;background-color:white">

      <input type="submit" name="StartGame" class="btn btn-info" value="New Game" />
      <a class="btn btn-info" href="https://localhost/index.php" role="button">Return Home</a>
    </div>
    
    </center>
    
    </body>
    <html>
    ';
  }

}
else
{

  //Checks if the matchid is valid or exsits

  if($matchID == "")
  {
    header("location:https://localhost/index.php");
  }
  if(!isset($_SESSION['IsAuthed']))
  {
    header('location:https://localhost/login.php');
  }

  //This function will check if the match id is taken or used, if so it takes you to the view page
  
  function CheckMatchID() {
  
    $matchIDSQL = $_REQUEST['id'];
  
    $Username1 = $_SESSION['Username1'];
    $Username2 = $_SESSION['Username2'];
  
    include("../database_connection.php");
  
    $query = "SELECT * FROM matches WHERE MatchID = $matchIDSQL";
    $statement = $connect->prepare($query);
    $statement->execute();	
    $count = $statement->rowCount();
    if($count > 0)
    {
      $result = $statement->fetchAll();
      foreach($result as $row)
      {
         if($row['Round'] == 4)
         {
            header("location: https://localhost/challenge/$matchIDSQL/view");
         }
      }
  
    }
    else
    {

      //If the match does not exsist then we insert the matchID and continue

      $query = "INSERT INTO matches (MatchID, Username1, Username2) VALUES ($matchIDSQL, '$Username1', '$Username2')";
      $statement = $connect->prepare($query);
      $statement->execute();	
    }
  }
  

  //Triggers function

  CheckMatchID();
  
  if(isset($_POST['LeaveGame']))
  {
     $_SESSION['rounds'] = 0;
     header("location: https://localhost/index.php");
  }

  //This is where the roll Dice function comes into place
  
  if(isset($_POST['Roll']))
  {
    if($_SESSION['rounds'] < 5)
    {
  
      //Count Rounds

      $rounds = $_SESSION['rounds'];
  
      $query = "UPDATE matches SET Round = $rounds WHERE MatchID = $matchID";
      $statement = $connect->prepare($query);
      $statement->execute();	
  
      //Player 1

      $roll1 = rand(1,6);
      $roll2 = rand(1,6);
  
      $scoretest = $roll1 + $roll2;
  
      $player_go = $_SESSION['Username1'] . "'s Go!";
  
      if ($scoretest % 2 == 0) {
        $output_info = "Your total score was even! You have gained an extra 10 points!";
        $player1_score = $player1_score + $roll1 + $roll2 + 10;
      }
      else {
        $output_info = "Your total score was odd! You have lost 5 points!";
        $player1_score = $player1_score + $roll1 + $roll2 - 5;
      }
      if($roll1 == $roll2) {
        $output_info = "You rolled a double! You have gained an extra roll!";
        $roll_extra = rand(1,6);
        $player1_score + $roll_extra;
      }

      $query = "UPDATE matches SET User1Score = User1Score + $player1_score WHERE MatchID = $matchID AND Username1 = '$Username1'";
      $statement = $connect->prepare($query);
      $statement->execute();

      //Player 2
      
      $roll3 = rand(1,6);
      $roll4 = rand(1,6);
  
      $scoretest1 = $roll3 + $roll4;
  
      if ($scoretest1 % 2 == 0) {
        $output_info1 = "Your total score was even! You have gained an extra 10 points!";
        $player2_score = $player2_score + $roll3 + $roll4 + 10;
      }
      else {
        $output_info1 = "Your total score was odd! You have lost 5 points!";
        $player2_score = $player2_score + $roll3 + $roll4 - 5;
      }
      if($roll3 == $roll4) {
        $output_info1 = "You rolled a double! You have gained an extra roll!";
        $roll_extra1 = rand(1,6);
        $player2_score + $roll_extra1;
      } 

      $query = "UPDATE matches SET User2Score = User2Score + $player2_score WHERE MatchID = $matchID AND Username2 = '$Username2'";
      $statement = $connect->prepare($query);
      $statement->execute();

      $_SESSION['rounds']++;
    }
    else
    {
      $query = "SELECT * FROM matches WHERE MatchID = $matchID";
      $statement = $connect->prepare($query);
      $statement->execute();	
      $result = $statement->fetchAll();
      foreach($result as $row)
      {
         $total_user1 = $row['User1Score'];
         $total_user2 = $row['User2Score'];

         if($total_user1 > $total_user2)
         {

            $UserID = $_SESSION['userid'];

            $query = "UPDATE matches SET WinnerUsername = '$Username1' WHERE MatchID = $matchID";
            $statement = $connect->prepare($query);
            $statement->execute();

            $query2 = "UPDATE authedusers SET Username1Wins = Username1Wins + 1 WHERE Username1 = '$Username1'";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();

         }
         else if($total_user2 > $total_user1)
         {

            $UserID = $_SESSION['userid'];

            $query = "UPDATE matches SET WinnerUsername = '$Username2' WHERE MatchID = $matchID";
            $statement = $connect->prepare($query);
            $statement->execute();

            $query2 = "UPDATE authedusers SET Username2Wins = Username2Wins + 1 WHERE Username2 = '$Username2'";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();
         }
         else
         {

            $UserID = $_SESSION['userid'];

            $query = "UPDATE matches SET WinnerUsername = 'Draw' WHERE MatchID = $matchID";
            $statement = $connect->prepare($query);
            $statement->execute();

            $query2 = "UPDATE authedusers SET Username1Wins = Username1Wins + 1 AND Username2Wins = Username2Wins + 1 WHERE Username2 = '$Username2'";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();
         }

      }
    }
  
  }
  
  echo '
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="stylesheet" href="https://localhost/style/bootstrap.min.css">
      <link rel="stylesheet" href="https://localhost/style/main_dice.css">
      <link rel="stylesheet" href="https://localhost/style/now-ui-kit.css">
      <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
      <title>Dice Game</title>
  </head>
  <style>
      .dice {
      width: 200px;
      height: 200px;
    }
  </style>
  <body>
  
  <center>
  
  <div class="container p-3 my-3 bg-dark text-white">
    <br>
    <h2>Welcome '.$Username1.' and '.$Username2.'</h2>
    <h2>Round '.$_SESSION['rounds'].'</h2>
  </div>
  
  <div class="parent-container d-flex">
      <div class="container p-3 my-3 bg-dark text-white">
          <div class="row">
              <div class="col">
                  <h1 class="title">'.$Username1.'</h1>
                  <br>
                  <img class="dice" src="https://localhost/dice/'.$roll1.'.png"><img class="dice" src="https://localhost/dice/'.$roll2.'.png">
                  <img onerror="this.style.display=`none`" class="dice" src="https://localhost/dice/'.$roll_extra.'.png">
                  <h1 class="subtitle">'.$output_info.'</h1>
              </div>
          </div>
      </div>
  
      <div class="container p-3 my-3 bg-dark text-white">
          <div class="row">
              <div class="col">
                  <h1 class="title">'.$Username2.'</h1>
                  <br>
                  <img class="dice" src="https://localhost/dice/'.$roll3.'.png"><img class="dice" src="https://localhost/dice/'.$roll4.'.png">
                  <img onerror="this.style.display=`none`" class="dice" src="https://localhost/dice/'.$roll_extra1.'.png">
                  <h1 class="subtitle">'.$output_info1.'</h1>
              </div>
          </div>
      </div>
  </div>
  
  <div class="container p-3 my-3 bg-dark text-white">
  <br>
  <form method="post">
    <div class="form-group">
      <h2>Click "Roll Dice" to begin!</h2>
      <input type="submit" name="Roll" class="btn btn-info" value="Roll Dice" />
      <input type="submit" name="LeaveGame" class="btn btn-info" value="Leave Game" />
    </div>
  </form>
  </div>
  <br />
  
  </center>
  
  </body>
  <html>
  ';
}

?>