<?php
    // Initialize the session
    session_start();
    require_once("config.php");
    require_once("functions.php");
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['account_type'] !== "a"){
        header("location: articles.php");
        exit;
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Maak post</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <link rel="stylesheet" href="style.css">
        
    </head>
    <body>
        <?php include 'header_restricted.php' ?>
        <div class="wrapper">
            <h2>Categories</h2>
            <p>Voeg categorie toe.</p>
            <form action="addcategory.php" method="post">
                <div class="form-group">
                    <input type="text" name="title" class="form-control" style="padding-right:85px;float:left">
                    <input type="submit" class="simplebutton" value="Submit">
                </div>
            </form>
            <div class="listheader" onclick='showList("cat")'><div id="cat_arrow" class="arrow">&#9654;</div>Show categories</div>
            <div class="admin" id="cat">

               <?php adminList($link, "categories", "category"); ?>
            </div>
                        
            <h2>Blacklist</h2>
            <p>Voeg woord toe.</p>
                <div class="form-group">
                <form action="addblacklist.php" method="post">
                    <input type="text" name="title" class="form-control" style="padding-right:85px;float:left">
                    <input type="submit" class="simplebutton" value="Submit">
                </div>
            </form>

            <div class="listheader" onclick='showList("black")'><div id="black_arrow" class="arrow">&#9654;</div>Show blacklisted words</div>
            <div class="admin" id="black">
            
            <?php adminList($link, "blacklist", "words"); ?>
            </div>
            <h2>Users</h2>
                          <div class="listheader" style="margin-top:10px;" onclick='showList("users")'><div id="users_arrow" class="arrow">&#9654;</div>Show all users</div>
            <div class="admin" id="users">

               <?php adminList($link, "users", "username"); ?>
            </div>
        </div>
<script src='js.js'></script>        
    </body>
</html>