<?php
    // Initialize the session
    session_start();
    require_once "config.php";
    require_once "functions.php";
?>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    </head>
    <body>
        <?php include 'header.php' ?>
        <div class="wrapper">
            <?php  
                buttonLoggedIn("btn btn-primary", "submit.php", "+ Maak nieuw artikel");
            ?>
            <a class="btn btn-default" href="search.php">&#128269; Zoek artikel</a>
            <h1> Articles: </h1>
            <?php 
                printArticles($link, orderBy());
            ?>
        </div>
    </body>
</html>
<!-- testing -->