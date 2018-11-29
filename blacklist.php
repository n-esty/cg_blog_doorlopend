<?php
    // Initialize the session
    session_start();
    require_once "config.php";
    $click_order = "ASC";
    $order_by = "DESC";
    $order_col = "created_at";
    $aut_sort = $date_sort = "";
    $up = "&#11205;";
    $down = "&#11206;";
    $direction = $down;

    if (isset($_GET["order"])) {
        $order = htmlspecialchars($_GET["order"]);
        if ($order === "ASC") {
            $order_by = "ASC";
            $direction = $up;
            $click_order = "DESC";
        }
    }
    
    if (isset($_GET["by"])) {
        $by = htmlspecialchars($_GET["by"]);
        if ($by === "author") {
            $order_col = $by;
            $aut_sort = $direction;
            if (isset($_GET["order"]) && $_GET["order"] === "ASC") {
                $click_order = "DESC";
            }
        }
    } else {
        $date_sort = $direction;
    }
   
    $blacklist = [];
    $articles = mysqli_query($link,"SELECT * FROM blacklist");
    while($row = mysqli_fetch_array($articles)){
         array_push($blacklist, $row['words']);
    };
    print_r($blacklist);
?>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 800px; padding: 20px;margin-left:auto;margin-right:auto; }
            table td th{padding:5px}
            table {width:100%}
        </style>
    </head>
    <body>
        <?php include 'header.php' ?>
        <div class="wrapper">
            <?php     
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                    echo '<a class="btn btn-primary" href="submit.php">+ Maak nieuw artikel</a>';
                }
            ?>
            <a class="btn btn-default" href="search.php">&#128269; Zoek artikel</a>
            <h1> Articles: </h1>
            <table border='1'>
                <tr>
                <th style='padding:10px'><a href='?by=author&order=<?php echo     $click_order ?>'>auteur <?php echo $aut_sort ?></a></th>
                <th style='padding:10px'>titel</th>
                <th style='padding:10px'><a href='?order=<?php echo     $click_order ?>'>tijd sinds gepost <?php echo $date_sort ?></a></th>
                </tr>
                <?php 
               ?>
        </div>
    </body>
</html>