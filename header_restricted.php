<div class="header">
<div class="header_cont">
    <div class="users">
    <a href="users.php" class="btn btn-default" style="margin-top:31px;margin-left:20px;">User Overview</a>
    </div>
    <div class="login">
        <?php
            echo '
                <div class="form-group form-boep">
                    <label style="padding-top:25px;color:white;font-size:20px;">Welkom, ' . $_SESSION["username"] . '</label>
                </div>             
                <div class="form-group form-boep" style="width:100px;">
                    <label>&nbsp;</label>
                    <a href="logout.php" class="btn btn-default form-control"> Logout</a>
                </div>
                ';
        ?>
    </div>
    </div>
</div>