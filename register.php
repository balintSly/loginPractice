<?php
include("login.php");
$conn = new mysqli($host, $user, $password);
$conn->select_db("DB5");
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
<form action="" method="post">
    <fieldset>
        <legend><b>Register</b></legend>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br>
        <label for="pword">Password:</label><br>
        <input type="password" id="pword" name="pword"><br>
        <label for="pwordagain">Password again:</label><br>
        <input type="password" id="pwordagain" name="pwordagain"><br>
        <input type="submit" value="Register"><br><br>
        <a href="mainpage.php">Back to the login page</a>
    </fieldset>
</form>
</body>
</html>
<?php //todo validate
if (isset($_POST["email"]) and isset($_POST["pword"]) and isset($_POST["pwordagain"]))
{
    if (strlen($_POST["email"])==0 or strlen($_POST["pword"])<8)
    {
        echo "<br>You must enter a vaild e-mail address, and a minimum 8 characters long password!";
    }
    else
    {
        if ($_POST["pword"] !== $_POST["pwordagain"])
        {
            echo "<br>Password fields are not equal!";
        }
        else
        {
            $email = $_POST["email"];
            $pwd = md5($_POST["pword"]);
            $logincount = 0;

            $sql = "SELECT * FROM Users WHERE email='$email'";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                $sql = "Insert into Users (email, pwhash, logincount) VALUES ('$email', '$pwd', '$logincount')";
                $conn->query($sql);
                echo "<br>User successfully registered to the database!";
            } else {
                echo "<br>Email already registered to the database!";
            }
        }

    }




}
?>
