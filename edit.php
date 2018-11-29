<?php
    // Initialize the session
    session_start();
    require_once "config.php";
    require_once "functions.php";
     
    // Check if the user is logged in and admin
    

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
    $info['author_id'] = $author_id;
    loginCheck('a', 'articles.php', $info);
     
    // Define variables and initialize with empty values
    $author_err = $title_err = $body_err = "";
    $captcha_err = "&nbsp;";
     
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
     
        $author = $_SESSION["id"];    
            
        // Validate title
        if(empty(trim($_POST["title"]))){
            $title_err = "Please enter a title.";     
        } else{
          $title = trim($_POST["title"]);
        }
         // Validate body
        if(empty(trim($_POST["body"])) || trim($_POST["body"])==="<p>&nbsp;</p>"){
            $body_err = "Please enter a body.";     
        } else{
          $body = trim($_POST["body"]);
        }
        
        
        // Check body and title input against blacklist
        $badwords_body = [];
        $badwords_title = [];
        $blacklist = file('blacklist.txt', FILE_IGNORE_NEW_LINES);
        foreach($blacklist as $word){
            if (strpos($body, $word) !== false) {
                array_push($badwords_body,$word);
            }
            if (strpos($title, $word) !== false) {
                array_push($badwords_title,$word);
            }
        }
        if ($badwords_body != []) {
            $body_err = "Niet geoorloofde woorden gedetecteerd. Gebruik deze woorden aub niet: " . implode(", ",$badwords_body);
        }    
        if ($badwords_title != []) {
            $title_err = "Niet geoorloofde woorden gedetecteerd. Gebruik deze woorden aub niet: " . implode(", ",$badwords_title);
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
        if(empty($title_err) && empty($body_err) && empty($captcha_err)){
            
            // Prepare an insert statement
            $sql = "UPDATE articles SET title = ?, body = ? WHERE id=?";
             
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssi", $param_title, $param_body, $param_id);
                
                // Set parameters
                $param_author = $author;
                $param_title = $title;
                $param_body = $body;
                $param_id = $id;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                    header("location: articles.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }
            }
             
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            $captcha_err = "Fout met de RECAPTCHA, probeer opnieuw (Zorg dat JS aan staat)";
        }
        
        // Close connection
        mysqli_close($link);
    }
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
        <?php include 'header_restricted.php' ?>
        <div class="wrapper">
            <a class="btn btn-primary" href='article.php?id=<?php echo $id ?>'>&#11207; Terug naar artikel</a>
            <h2>Edit post: <?php echo $title; ?></h2>
            <p>Edit hier je post.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>" method="post">
                <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                    <label>Post titel</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
                    <span class="help-block"><?php echo $title_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($body_err)) ? 'has-error' : ''; ?>">
                    <label>Post inhoud</label>
                    <textarea type="textarea" id="editor" name="body" class="form-control" rows="10" value="<?php echo htmlspecialchars($body); ?>"><?php echo $body; ?></textarea>
                    <span class="help-block"><?php echo $body_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($captcha_err)) ? 'has-error' : ''; ?>">
                    <div class="g-recaptcha" data-sitekey="6LdmAHsUAAAAAB18I9OpYMBiynNtI_6kcJqlwckw"></div>
                    <span class="help-block" style=""><?php echo $captcha_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Post updaten">
                </div>
            </form>
        </div>
        <script src="ckeditor.js"></script>
        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } ); 
        </script>    
    </body>
</html>