<?php
include("login.php");
include("sql_inj_filter.php");
$conn = new mysqli($host, $user, $password);
session_start();
if (isset($_SESSION["user"])){
    $userid=$_SESSION["user"];
    $conn->select_db("DB5");
    $sql="SELECT * FROM Users WHERE id='$userid'";
    $result=$conn->query($sql);
    $user=$result->fetch_assoc();
}
else{
    header('Location: mainpage.php');
}

?>
<!DOCTYPE html>
<html>
<head>
    <script>
        function showsettings() {
            document.getElementById('main_container').style.display="none";
            document.getElementById('change_container').style.display="block";
            document.getElementById('currentemail').innerText='Current email: <?php echo $user["email"]?>';
        }
        function hidesettings() {
            document.getElementById('main_container').style.display="block";
            document.getElementById('change_container').style.display="none";
            document.getElementById('response').innerText='';
        }
    </script>
</head>
<body onload="hidesettings()">
<div id="main_container">
    <h1>You are logged in!</h1>
    <a href="#" onclick="showsettings()">Account settings</a><br>
    <a href="indexpage.php?logout=<?php echo $userid?>">Logout</a>
</div>
<div id="change_container">
    <form action="indexpage.php" method="post">
        <fieldset>
            <legend><b>Account settings</b></legend>
            <label id="currentemail"></label><br>
            <label for="email">New email:</label><br>
            <input type="text" id="email" name="email"><br>
            <label for="pword">Password:</label><br>
            <input type="password" id="pword" name="pword"><br>
            <label for="pwordagain">Password again:</label><br>
            <input type="password" id="pwordagain" name="pwordagain"><br>
            <input type="submit" value="Change" onclick="showsettings()"><br><br>
            <button type="button" onclick="hidesettings()">Back</button><br><br>
            <label id="response"></label>
        </fieldset>
    </form>
</div>
</body>
</html>
<?php
if (isset($_SESSION["changing"])){
    ?>
    <script type="text/javascript">
        showsettings();
    </script>
    <?php
    unset($_SESSION["changing"]);
}
if(isset($_GET["logout"])){
    unset($_SESSION["user"]);
    header('Location: indexpage.php');
}
else if (isset($_POST["email"]) and isset($_POST["pword"]) and isset($_POST["pwordagain"]))
{
    $_SESSION["changing"]=true;
    if (filter_input(INPUT_POST,"email", FILTER_VALIDATE_EMAIL)===false)
    {
        ?>
        <script type="text/javascript">
            document.getElementById('response').innerText='Invalid email.';
        </script>
        <?php
    }
    else if (strlen($_POST["pword"])<8)
    {
        ?>
        <script type="text/javascript">
            document.getElementById('response').innerText='You must enter a minimum 8 characters long password!';
        </script>
        <?php
    }
    else
    {
        if ($_POST["pword"] !== $_POST["pwordagain"])
        {
            ?>
            <script type="text/javascript">
                document.getElementById('response').innerText='Password fields are not equal!';
            </script>
            <?php
        }
        else
        {
            $email = htmlspecialchars(filter_sql($_POST["email"]));
            $pwd = md5(htmlspecialchars(filter_sql($_POST["pword"])));
            $sql = "SELECT * FROM Users WHERE email='$email'";

            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                $sql="update users set email='$email', pwhash='$pwd' where id='$userid'";
                $conn->query($sql);
                ?>
                <script type="text/javascript">
                    document.getElementById('response').innerText='User data successfully updated!';
                </script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                document.getElementById('response').innerText='Email already registered to the database!';
                </script>
                <?php
            }
        }
    }
    foreach ($_POST as $key=>$value) {
        unset($_POST[$key]);
    }
}

