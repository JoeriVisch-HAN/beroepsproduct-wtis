<?php
include("./components/header.html");

require_once('db_connectie.php');

function getVluchtnummers()
{
    $conn = maakVerbinding();
    $sql = 'select vluchtnummer, m.naam, vertrektijd, l.naam as airport, l.land
    from Vlucht v
    JOIN Maatschappij m
    on m.maatschappijcode = v.maatschappijcode
    JOIN Luchthaven l
    on l.luchthavencode = v.bestemming
    WHERE vertrektijd > GETDATE() AND vluchtnummer in (
        SELECT v.vluchtnummer
    FROM Maatschappij m
    left JOIN Vlucht v
    on m.maatschappijcode = v.maatschappijcode
    JOIN Passagier p
    on p.vluchtnummer = v.vluchtnummer
    GROUP BY max_aantal, v.vluchtnummer
    HAVING COUNT(p.vluchtnummer) < max_aantal
    )
    ORDER BY vluchtnummer';
    $stmt = $conn->query($sql);
    $opties = ' ';
    while ($rij = $stmt->fetch()) {
        $naamOptie = $rij['vluchtnummer'] . ' | ' . $rij['vertrektijd'] . ' | ' . $rij['naam'] . ' | ' . $rij['airport'] . ' | ' . $rij['land'];
        $opties .= '<option name=" ' . $rij['vluchtnummer'] . '" value= " ' . $rij['vluchtnummer'] . '"> ' . $naamOptie . '</option>';
    }
    return $opties;
}

$opties = getVluchtnummers();

function getpassagiersnummer()
{
    $conn = maakVerbinding();
    $sql = ' select MAX(passagiernummer)+1
    from Passagier';
    $stmt = $conn->query($sql);
    $waarde = intval($stmt->fetch());
    while()
    return $waarde;
}


$conn = maakVerbinding();
$passagiersnummer = getpassagiersnummer();
$naam = ' ';
$geslacht = null;
$fouten[] = '';
$melding = ' ';
if (isset($_POST['toevoegen'])) {
    if (!(empty($_POST['voornaam'] and empty($_POST['achternaam'])))) {
        $naam = $_POST['achternaam'] . ', ' . $_POST['voornaam'];
    } else {
        $fouten[] = 'geen naam';
    }

    if (!empty($_POST['geslacht'])) {
        if (!$_POST['geslacht'] == 'geen') {
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
        $sql = '
        insert into Passagier (passagiernummer, naam, geslacht, vluchtnummer)
        VALUES(:passagiernummer, :naam, :geslacht, :vluchtnummer)';
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
    <label>
        vlucht:
        <select>
            <?= $opties ?>
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