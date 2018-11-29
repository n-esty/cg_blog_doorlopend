<?php
    // Initialize the session
    session_start();   
    require_once "config.php";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Maak post</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <link rel="stylesheet" href="style.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <?php include 'header.php' ?>
        <div class="wrapper">
            <a class="btn btn-primary" href='articles.php'>&#11207; Terug naar blog overzicht</a>
            <h2>Search:</h2>
            <form action="results.php" method="GET">
                <div class="form-group">
                    <input type="text" name="query" class="form-control" placeholder="Doorzoek hier blog titels of inhoud" style="padding-right:85px;float:left"><input type="submit" class="simplebutton" value="Search">
            </form>
        </div>
    </body>
</html>