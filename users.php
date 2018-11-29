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
            <a class="btn btn-primary" href="articles.php">&#11207; Terug naar blog overzicht</a>
            <h1> Users: </h1>
            <?php printUsers($link, orderBy()) ?>
        </div>
    </body>
</html>