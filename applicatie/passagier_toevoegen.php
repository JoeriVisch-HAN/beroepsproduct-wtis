<?php
include("./components/header.html");

require_once('db_connectie.php');
$conn = maakVerbinding();
$passagiersnummer = ' ';
$naam = ' ';
$geslacht = null;
$fouten[] = '';
$melding = ' ';
if (isset($_POST['toevoegen'])) {
   if(!(empty($_POST['voornaam'] and empty($_POST['achternaam'])))){
        $naam = $_POST['achternaam'] . ', ' . $_POST['voornaam'];
   } else{
        $fouten[] = 'geen naam';
   }

   if(!empty($_POST['geslacht'])){
    if(!$_POST['geslacht'] == 'geen'){
        $geslacht = $_POST['geslacht'];
    }
   }

    if (count($fouten) > 1) {

        echo '<ul>';
        foreach ($fouten as $fout) {
            echo '<li>' . $fout . '</li>';
        }
        echo '</ul>';
    } else {
        // Insert query
        $sql = 'insert into Muziekschool (componistId, naam, geboortedatum, schoolId) 
values (:componistId, :naam, :geboortedatum, :schoolId)';
        $stmt = $conn->prepare($sql);
        $succes = $stmt->execute([
            'componistId' => $componistId,
            'naam' => $naam,
            'geboortedatum' => $geboortedatum,
            'schoolId' => $schoolId
        ]);
        if ($succes) {
            $melding = 'Gegevens zijn opgeslagen in de database.';
        } else {
            $melding = 'Er ging iets fout bij het opslaan.';
        }
    }
}
?>


<form action=" " method="post">
    <h1>passagier toevoegen</h1>
    <p>maak hier een nieuwe passagier aan.</p>
    <label>
        voornaam:
        <input type="text" name="voornaam" pattern="[a-zA-Z]+" required>
    </label>
    <label>
        achternaam:
        <input type="text" name="achternaam" pattern="[a-zA-Z]+" required>
    </label>
    <label>
        Geslacht:
        <select name="geslacht">
            <option value="X"> X </option>
            <option value="M"> M </option>
            <option value="V" selected> V </option>
            <option value="geen"> geen geslacht </option>
        </select>
    </label>
    <label> wissen:
        <input type="reset" id="reset" name="reset" value="wissen">
    </label>
    <label>
        toevoegen:
        <input type="submit" name="inloggen" value="toevoegen">
    </label>
</form>


<?php
include("./components/footer.html");
?>