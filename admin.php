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
    
    $word = $category = "";
    $word_err = $category_err = "";
     // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
     
        // Process categories form
        if (!empty(trim($_POST['category']))) {  
                $sql = "SELECT id FROM categories WHERE category = ?";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "s", $param_category);
                    $param_username = trim($_POST["category"]);
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_store_result($stmt);
                        if(mysqli_stmt_num_rows($stmt) == 1){
                            $category_err = "Category already excists.";
                        } else{
                            $category = trim($_POST["category"]);
                        }
                    } else{
                        $category_err = "Category already excists.";
                    }
                }
                mysqli_stmt_close($stmt);
              
       
        if(empty($category_err)){
            $sql = "INSERT INTO categories (category) VALUES (?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_category);
                $param_category = $category;
                if(mysqli_stmt_execute($stmt)){
                    header("location: admin.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);
        }
        }
    
    // Process blacklist form
        if (!empty(trim($_POST['word']))) {  
                $sql = "SELECT id FROM blacklist WHERE words = ?";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "s", $param_category);
                    $param_username = trim($_POST["word"]);
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_store_result($stmt);
                        if(mysqli_stmt_num_rows($stmt) == 1){
                            $word_err = "Word already excists.";
                        } else{
                            $word = trim($_POST["word"]);
                        }
                    } else{
                        $word_err = "Word already excists.";
                    }
                }
                mysqli_stmt_close($stmt);
                
       
        if(empty($category_err)){
            $sql = "INSERT INTO blacklist (words) VALUES (?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_word);
                $param_word = $word;
                if(mysqli_stmt_execute($stmt)){
                    header("location: admin.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }
            }
            mysqli_stmt_close($stmt);
        }
        }
    
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="categories">
                <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>" >
                    <input type="text" name="category" class="form-control" value="<?php echo $category; ?>" style="padding-right:85px;float:left"><input type="submit" class="simplebutton" value="Submit">
                    <span style="clear:both" class="help-block"><?php echo $category_err; ?></span>
                </div>
            </form>
            <div class="listheader" onclick='showList("cat")'><div id="cat_arrow" class="arrow">&#9654;</div>Show categories</div>
            <div class="admin" id="cat">

               <?php adminList($link, "categories", "category"); ?>
            </div>
                        
            <h2>Blacklist</h2>
            <p>Voeg woord toe.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="blacklist">
                <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>" >
                    <input type="text" name="word" class="form-control" value="<?php echo $word; ?>" style="padding-right:85px;float:left"><input type="submit" class="simplebutton" value="Submit">
                    <span style="clear:both" class="help-block"><?php echo $word_err; ?></span>
                </div>
            </form>

            <div class="listheader" onclick='showList("black")'><div id="black_arrow" class="arrow">&#9654;</div>Show blacklisted words</div>
            <div class="admin" id="black">
            
            <?php adminList($link, "blacklist", "words"); ?>
            </div>
            <h2>Users</h2>
                          <div class="listheader" onclick='showList("users")'><div id="users_arrow" class="arrow">&#9654;</div>Show all users</div>
            <div class="admin" id="users">

               <?php adminList($link, "users", "username"); ?>
            </div>
        </div>
<script src='js.js'></script>        
    </body>
</html>