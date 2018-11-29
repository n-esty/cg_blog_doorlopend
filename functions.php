<?php

function orderBy () {
    $click_order = "ASC";
    $order_dir = "DESC";
    $order_col = "created_at";
    $aut_sort = $date_sort = $uid_sort = $uname_sort = $reg_sort = "";
    $up = "&#11205;";
    $down = "&#11206;";
    $direction = $down;

    if (isset($_GET["order"])) {
        $order = htmlspecialchars($_GET["order"]);
        if ($order === "ASC") {
            $order_dir = "ASC";
            $direction = $up;
            $click_order = "DESC";
        }
    }
    
    if (isset($_GET["by"])) {
        $by = htmlspecialchars($_GET["by"]);
        if ($by === "id" || $by === "username" || $by === "author") {
            if ($by === "id") {
                $uid_sort = $direction;
            } elseif ($by === "username") {
                $uname_sort = $direction;
            } elseif ($by === "author") {
                $aut_sort = $direction;
            } 
            $order_col = $by;
            if (isset($_GET["order"]) && $_GET["order"] === "ASC") {
                $click_order = "DESC";
            }
        }
    } else {
        $reg_sort = $direction;
        $date_sort = $direction;
    }
    $order_by = array(
    'click_order' => $click_order,
    'order_col' => $order_col,
    'order_dir' => $order_dir,
    'aut_sort' => $aut_sort,
    'uid_sort' => $uid_sort,
    'uname_sort' => $uname_sort,
    'reg_sort' => $reg_sort,
    'date_sort' => $date_sort);
    return $order_by;
}
function timeSincePosted($post_date, $current_date) {
    $interval = $current_date->diff($post_date);
    if($interval->format('%Y')==0){
        if($interval->format('%m')==0) {
            if($interval->format('%d')==0) {
                if($interval->format('%H')==0) {
                    return $interval->format('%i minutes') . " and " . $interval->format('%s seconds');
                } else {
                    return $interval->format('%H hours') . " and " . $interval->format('%i minutes');
                }
            } else {
                return $interval->format('%d days') . " and " . $interval->format('%H hours');
            }
        } else {
            return $interval->format('%m months') . " and " . $interval->format('%d days');
        }
    } else {
        return $interval->format('%Y years') . " and " . $interval->format('%m months');
    }
}

function printArticles($link, $order_by) {
    extract($order_by);
    $articles = mysqli_query($link,"SELECT * FROM articles ORDER BY $order_col $order_dir");
    echo "<table border='1'>
        <tr>
        <th style='padding:10px'><a href='?by=author&order=$click_order'>auteur $aut_sort</a></th>
        <th style='padding:10px'>titel</th>
        <th style='padding:10px'><a href='?order=$click_order'>tijd sinds gepost $date_sort</a></th>
        </tr>
        ";
    
    while($row = mysqli_fetch_array($articles)){    
        $post_date = new DateTime($row['created_at']);
        $current_date = new DateTime(date('Y-m-d H:i:s'));
        $user_id = $row['author'];
        $users = mysqli_query($link,"SELECT * FROM users WHERE id='$user_id'");
        $user_info = mysqli_fetch_array($users);
        echo "<tr>
        <td style='padding:10px'>" . $user_info['username'] . "</td>
        <td style='padding:10px'><a href='article.php?id=" . $row['id'] . "'>" . $row['title'] . "</a></td>
        <td style='padding:10px'>" . timeSincePosted($post_date,$current_date) . "</td>
        </tr>";
    }
    echo "</table>";
}

function printUsers($link, $order_by) {
    extract($order_by);
    $users = mysqli_query($link,"SELECT * FROM users ORDER BY $order_col $order_dir");
    echo "<table border='1'>
        <tr>
        <th style='padding:10px'><a href='?by=id&order=$click_order'>User ID $uid_sort</a></th>
        <th style='padding:10px'><a href='?by=username&order=$click_order'>Username $uname_sort</a></th>
        <th style='padding:10px'><a href='?order=$click_order'>Registratie Datum $reg_sort</a></th>
        </tr>";
                
    while($row = mysqli_fetch_array($users)){  
        echo "<tr>
            <td style='padding:10px'>" . $row['id'] . "</td>
            <td style='padding:10px'><a href='profile.php?user=" . $row['id'] . "'>" . $row['username'] . "</a></td>
            <td style='padding:10px'>" . $row['created_at'] . "</td>
            </tr>";
    };
    echo "</table>";
}

function printResults($link, $order_by) {
    extract($order_by);
    $query = $_GET['query'];
    $min_length = 3;     
    if(strlen($query) >= $min_length){ 
        $query = htmlspecialchars($query); 
        $articles = mysqli_query($link,"SELECT * FROM articles WHERE (`title` LIKE '%".$query."%') OR (`body` LIKE '%".$query."%') ORDER BY $order_col $order_dir" ) or die(mysqli_error($link));
    } else {
          header("location: search.php"); 
    } 
    echo "<table border='1'>
        <tr>
        <th style='padding:10px'><a href='?query=$query&by=author&order=$click_order'>auteur $aut_sort</a></th>
        <th style='padding:10px'>titel</th>
        <th style='padding:10px'><a href='?query=$query&order=$click_order'>tijd sinds gepost $date_sort</a></th>";
    while($row = mysqli_fetch_array($articles)) {    
        $post_date = new DateTime($row['created_at']);
        $current_date = new DateTime(date('Y-m-d H:i:s'));
        $user_id = $row['author'];
        $users = mysqli_query($link,"SELECT * FROM users WHERE id='$user_id'");
        $user_info = mysqli_fetch_array($users);
        echo "<tr>
            <td style='padding:10px'>" . $user_info['username'] . "</td>
            <td style='padding:10px'><a href='article.php?id=" . $row['id'] . "'>" . $row['title'] . "</a></td>
            <td style='padding:10px'>" . timeSincePosted($post_date,$current_date) . "</td>
            </tr>";
    };
    echo "</table>";
}

function buttonLoggedIn($class,$url,$text){
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        echo "<a class='$class' href='$url'>$text</a>";
                }
}

function search($link, $query) {
    $order_by = orderBy();
    extract($order_by);
}

function loginCheck($perm, $out, $info){
    extract($info);
    extract($perm);
    $account_type = $_SESSION['account_type'];
    
    if($author_id != $_SESSION["id"] && $account_type !="a"){
        header("location: articles.php");
        exit;
    } 
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
}
?>