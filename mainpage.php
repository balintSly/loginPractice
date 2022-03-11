<?php
include("login.php");
$conn = new mysqli($host, $user, $password);
if ($conn->connect_error) {
    die ("Connection error: " . $conn->connect_error);
}
$sql = "CREATE DATABASE IF NOT EXISTS DB5";
$conn->query($sql);
$conn->select_db("DB5"); // = mysqli_select_db($conn, "DB3");
$sql = "CREATE TABLE IF NOT EXISTS Users (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    pwhash VARCHAR(32) NOT NULL ,
    logincount int(6),
    reg_date TIMESTAMP   
)";
$conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
<form action="" method="post">
    <fieldset>
        <legend><b>Login</b></legend>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br>
        <label for="pword">Password:</label><br>
        <input type="password" id="pword" name="pword"><br>
        <input type="submit" value="Login"><br><br>
        <a href="register.php">Register</a>
    </fieldset>
</form>
</body>
</html>
<?php
if (isset($_POST["email"]) and isset($_POST["pword"]))
{
    $email=$_POST["email"];
    $pwd=md5($_POST["pword"]);
    $sql="SELECT * FROM Users WHERE email='$email' and pwhash='$pwd'";
    $result=$conn->query($sql);
    if ($result->num_rows==0)
    {
        echo "<br>There is no users with this data in the database, register first!";
    }
    else
    {
        $data=$result->fetch_assoc();
        $logincount=$data["logincount"];
        $id=$data["id"];
        $logincount++;


        $sql="update users set logincount='$logincount' where id='$id'";
        $conn->query($sql);
        header('Location: index.php');
    }
}

?>


