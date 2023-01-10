<?php
include("./components/header.php");

require_once 'db_connectie.php';
if (!isset($_SESSION['uid'])) {
    $_SESSION['uid'] = null;
}
$gebruiker = '';
$wachtwoord = '';
$fouten = [];

if (isset($_POST['inloggen'])) {
    if (!empty($_POST['gebruiker'])) {
        $gebruiker = $_POST['gebruiker'];
        $gebruiker = strip_tags($gebruiker);
        $gebruiker = addslashes($gebruiker);
        $gebruiker = htmlspecialchars($gebruiker);
        $gebruiker = htmlentities($gebruiker);
    } else {
        $fouten[] = 'geen gebruiker';
    }

    if (!empty($_POST['wachtwoord'])) {
        $wachtwoord = $_POST['wachtwoord'];
        $wachtwoord = strip_tags($wachtwoord);
        $wachtwoord = addslashes($wachtwoord);
        $wachtwoord = htmlspecialchars($wachtwoord);
        $wachtwoord = htmlentities($wachtwoord);
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
        $stmt->bindparam(':naam', $gebruiker, PDO::PARAM_STR);
        $stmt->execute();
        $hash = '';
        while ($waarde = $stmt->fetch()) {
            $hash = $waarde['password'];
            $_SESSION['uid'] = $waarde['uid'];
        }

        if (password_verify($wachtwoord, $hash)) {
            header("Location: medewerker.php");
        } else { 
            $_SESSION['uid'] = null;
            echo 'ongeldig wachtwoord.';
        }
    }
}


?>

<form action=" " method="post">
    <h1>login</h1>
    <p>Log hier in om verder te kunnen op de website!</p>
    <label>
        username:
        <input type="text" name="gebruiker" pattern="[a-zA-Z]+" required>
    </label>
    <label>
        password:
        <input type="password" name="wachtwoord" minlength="5" required>
    </label>
    <label>
        inloggen:
        <input type="submit" name="inloggen" value="inloggen">
    </label>
</form>