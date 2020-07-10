<?php

//Database Include

include('database_connection.php');
session_start();

//If the user has already been authed redirect to home page

if(isset($_SESSION['IsAuthed']))
{
	header('location:index.php');
}

//Simple login script

if(isset($_POST['login']))
{
    //I didnt want to hard code the authkey however there was no need to do it any other way

    $AuthKey = "Project2020";
    $Password = $_POST["AuthKey"];

    //if the authKey is the same as the password which is posted in are html then accept and set the session to true

    if($AuthKey == $Password)
    {
        $_SESSION['IsAuthed'] = true;
        header("location:index.php");
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
<div class="container p-3 my-3 bg-dark text-white">
<h1 class="title">Dice Online</h1>
<form method="post">
	<div class="form-group">
		<label>Enter Authorization Key</label>
		<input type="password" name="AuthKey" class="form-control" required />
	</div>
	<div class="form-group">
		<input type="submit" name="login" class="btn btn-info" value="Login" />
	</div>
	</form>
</div>
</body>  
</html>