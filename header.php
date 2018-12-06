<div class="header">
<div class="header_cont">
    <div class="users">
    <a href="users.php" class="btn btn-default" style="margin-top:31px;margin-left:20px;">User Overview</a>
    </div>
    <div class="login">
        <?php
            // Check if the user is already logged in, if yes then redirect him to welcome page
            if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
             
            echo '
                  <form action="header_login.php" method="post">
                <div class="form-group form-boep">
                          <label style="color:white;">Username</label>
                          <input type="text" name="username" class="form-control" value="">
                      </div>             
                <div class="form-group form-boep">
                          <label style="color:white;">Password</label>
                          <input type="password" name="password" class="form-control">
                      </div>             
                <div class="form-group form-boep" style="width:100px;">
                    <label>&nbsp;</label>
                          <input type="submit" class="btn btn-default form-control" value="Login" >
                      </div>
                <div class="form-group form-boep" style="width:100px;">
                    <label>&nbsp;</label>
                          <a href="register.php" class="btn btn-default form-control"> Register</a>
                      </div>
                  </form>';
            } else {
            echo '
                <div class="form-group form-boep">
                          <label style="padding-top:25px;color:white;font-size:20px;">Welkom, ' . $_SESSION["username"] . '</label>
                      </div>             
                <div class="form-group form-boep" style="width:100px;">
                    <label>&nbsp;</label>
                          <a href="logout.php" class="btn btn-default form-control"> Logout</a>
                      </div>';
            }
            
            ?>
    </div>
    </div>
</div>