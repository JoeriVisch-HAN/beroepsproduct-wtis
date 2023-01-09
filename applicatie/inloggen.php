<?php
include("./components/header.php");

require_once 'db_connectie.php';
if (!isset($_SESSION['uid'])) {
    $_SESSION['uid'] = null;
}
$username = '';
$password = '';
$fouten = [];

if (isset($_POST['inloggen'])) {
    if (!empty($_POST['username'])) {
        $username = $_POST['username'];
        $username = strip_tags($username);
        $username = addslashes($username);
        $username = htmlspecialchars($username);
        $username = htmlentities($username);
    } else {
        $fouten[] = 'geen username';
    }

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $password = strip_tags($password);
        $password = addslashes($password);
        $password = htmlspecialchars($password);
        $password = htmlentities($password);
    } else {
        $fouten[] = 'geen wachtwoord';
    }

    if (count($fouten) > 1) {
        echo '<ul>';
        foreach ($fouten as $fout) {
            echo '<li>' . $fout . '</li>';
        }
        echo '</ul>';
    } else {
        $conn = maakVerbinding();
        $sql = '
        select uid, password from medewerkers 
        where naam = :naam';
        $stmt = $conn->prepare($sql);
        $stmt->bindparam(':naam', $username, PDO::PARAM_STR);
        $stmt->execute();
        $hashcoded = '';
        while ($waarde = $stmt->fetch()) {
            $hashcoded = $waarde['password'];
            $_SESSION['uid'] = $waarde['uid'];
        }

        if (password_verify($password, $hashcoded)) {
            echo 'Password is valid!';
            header("Location: medewerker.php");
        } else { 
            $_SESSION['uid'] = null;
            echo 'Invalid password.';
        }
    }
}


?>

<form action=" " method="post">
    <h1>login</h1>
    <p>Log hier in om verder te kunnen op de website!</p>
    <label>
        username:
        <input type="text" name="username" pattern="[a-zA-Z]+" required>
    </label>
    <label>
        password:
        <input type="password" name="password" minlength="5" required>
    </label>
    <label>
        inloggen:
        <input type="submit" name="inloggen" value="inloggen">
    </label>
</form>