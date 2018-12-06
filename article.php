<?php
    // Initialize the session
    session_start();
    require_once "config.php";
    require_once "functions.php";
    $admin = false;
    $id = htmlspecialchars($_GET["id"]);
    
    // Get article with id = $id
    $sql = "SELECT author, title, body, created_at FROM articles WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            $param_id = $id;
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $author_id, $title, $body, $created_at);
                mysqli_stmt_fetch($stmt); 
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }   
    mysqli_stmt_close($stmt);
            
    // Get author info with author = $author_id
    $sql = "SELECT username FROM users WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            $param_id = $author_id;
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $author_name);
                mysqli_stmt_fetch($stmt); 
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }   
    mysqli_stmt_close($stmt);    
    
    // Checking account type
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        $account_type = $_SESSION["account_type"];
        if($author_id == $_SESSION["id"] || $account_type == "a"){
            $admin = true;
        }
    }
    $cbody = "";
    $cbody_err = "";
    $captcha_err = "&nbsp;";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $article_id = htmlspecialchars($_GET["id"]);
        $user_id = $_SESSION["id"];    
            
         // Validate body
        if(empty(trim($_POST["cbody"])) || trim($_POST["cbody"])==="<p>&nbsp;</p>"){
            $cbody_err = "Please enter a body.";     
        } else{
          $cbody = trim($_POST["cbody"]);
        }
        // Check body and title input against blacklist
        $badwords_body = [];
        $badwords_title = [];
        $blacklist = [];
        $words = mysqli_query($link,"SELECT * FROM blacklist");
        while($blr = mysqli_fetch_array($words)){
            array_push($blacklist, $blr['words']);
        };
        foreach($blacklist as $word){
            if (strpos($cbody, $word) !== false) {
                array_push($badwords_body,$word);
            }
        }
        if ($badwords_body != []) {
            $cbody_err = "Niet geoorloofde woorden gedetecteerd. Gebruik deze woorden aub niet: " . implode(", ",$badwords_body);
        }    

            //Validate CAPTCHA
        if(isset($_POST['g-recaptcha-response'])) {
            // RECAPTCHA SETTINGS
            $captcha = $_POST['g-recaptcha-response'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $key = '6LdmAHsUAAAAALKibGNzNch7gfKTPJwVHAvzP1w0';
            $url = 'https://www.google.com/recaptcha/api/siteverify';
     
            // RECAPTCH RESPONSE
            $recaptcha_response = file_get_contents($url.'?secret='.$key.'&response='.$captcha.'&remoteip='.$ip);
            $data = json_decode($recaptcha_response);
     
            if(isset($data->success) &&  $data->success === true) {
                $captcha_err = "";
            }
            else {
                $captcha_err = "Fout met de RECAPTCHA, probeer opnieuw";
            }
        }
        
        // Check input errors before inserting in database
        if(empty($cbody_err) && empty($captcha_err)){
            // Prepare an insert statement
            $sql = "INSERT INTO comments (user_id, article_id, body) VALUES (?, ?, ?)";
             
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $param_user, $param_article, $param_body);
                
                // Set parameters
                $param_user = $user_id;
                $param_article = $article_id;
                $param_body = $cbody;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                   // header("location: articles.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }
            }
           
            mysqli_stmt_close($stmt);
         }  else {
            $captcha_err = "Fout met de RECAPTCHA, probeer opnieuw";
         }         
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <script>var id = <?php echo $id ?> </script>
        <script src="js.js"></script>
         <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <?php include 'header.php' ?>
        <div class="wrapper">
            <a class="btn btn-primary" href='articles.php'>&#11207; Terug naar blog overzicht</a>
            <?php
                echo "<h1> $title </h1>
                <p><i> $author_name </i></p>
                <div style='height:10px;width:100%;background-color:grey'></div>
                <p>$body</p>";
                if($admin) {
                    echo "<br><br><br>
                    <a class=\"btn btn-danger\" onclick='deleteArt()'>DELETE</a>
                    &nbsp;&nbsp;
                    <a class=\"btn btn-default\" href='edit.php?id=$id'>EDIT</a>";
                }
                ?>
                
                <br><br><div style='height:10px;width:100%;background-color:grey'></div>
                
                <?php printComments($link); 
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?id='. $id . '" method="post">

                                   <div class="form-group">
                    <label><br>Plaats comment:</label>
                    <textarea type="textarea" id="editor" name="cbody" class="form-control" rows="10" value="">' . $cbody . '</textarea>
                   <span class="help-block">' . $cbody_err . '</span>
                </div>
                                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LdmAHsUAAAAAB18I9OpYMBiynNtI_6kcJqlwckw"></div>
                    <span class="help-block" style="">' . $captcha_err . '</span>
                </div>
                                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Comment plaatsen">
                </div>
            </form>';
                }
                
                ?>
        </div>
    </body>
</html>