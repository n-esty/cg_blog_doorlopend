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
            ?><div style="float:right;width:50%;padding:0;">
            <form action="results.php" method="GET" style="margin:0;padding:0;" >
                <div class="form-group" style="margin:0;padding:0;">
                    <input type="text" name="query" class="form-control" placeholder="Doorzoek hier blog titels of inhoud" style="padding-right:85px;float:left"><input type="submit" class="simplebutton" value=" &#128269; Zoek">
                </div>
            </form>
            </div>
            <h1> Articles: </h1>
            <?php 
                printArticles2($link, orderBy());
            ?>
        </div>
    </body>
</html>
<!-- testing -->