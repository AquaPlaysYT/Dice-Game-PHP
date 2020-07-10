<?php

//This file ends the current sessions which clears all ($_SESSION['name']) vars then redirects to the login page

session_start();

session_destroy();

header('location:login.php');

?>