<?php
include("./components/header.php");
require_once('db_connectie.php');
$gegevens = '';
$fouten = [];
$kofferaantal = 0;
$gewichtperkoffer = 0;
$maximaalgewicht = [];
if (!isset($_SESSION['passagiernummer'])) {
    $_SESSION['passagiernummer'] = 0;
}

if (!isset($_SESSION['vluchtnummer'])) {
    $_SESSION['vluchtnummer'] = 0;
}

function getPassagier($passagiernummer)
{
    $conn = maakVerbinding();
    $sql = 'select passagiernummer from passagier where passagiernummer = :passagiernummer';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['passagiernummer' => $passagiernummer]);
    $waarde = null;
    while ($id = $stmt->fetch()) {
        $waarde = $id;
    }
    if ($waarde == null) {
        return null;
    }
    return $waarde[0];
}
function getmaximaalgewicht($vluchtnummer)
{
    $conn = maakVerbinding();
    $sql = 'select SUM(b.gewicht) as "inchecktgewicht", v.max_gewicht_pp, v.max_totaalgewicht
    from Vlucht v 
    join Passagier p
    on v.vluchtnummer = p.vluchtnummer
    JOIN BagageObject b
    ON b.passagiernummer = p.passagiernummer
    WHERE v.vluchtnummer = :vluchtnummer
    GROUP BY v.max_gewicht_pp, v.max_totaalgewicht';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['vluchtnummer' => $vluchtnummer]);
    $waarden = [];
    while ($rij = $stmt->fetch()) {
        $waarden['inchecktgewicht'] = floatval($rij['inchecktgewicht']);
        $waarden['max_gewicht_pp'] = floatval($rij['max_gewicht_pp']);
        $waarden['max_totaalgewicht'] = floatval($rij['max_totaalgewicht']);
    }
    return $waarden;
}

if (isset($_POST['passagiersnummerinchecken'])) {
    if (!empty($_POST['passagiernummer']) && is_numeric($_POST['passagiernummer'])) {
        $_SESSION['passagiernummer'] = getPassagier($_POST['passagiernummer']);
        $conn = maakVerbinding();
        $sql = 'select passagiernummer, p.naam as pasnaam, p.vluchtnummer, m.naam as maatschappij, vertrektijd, l.naam as airport, l.land
        from Passagier p
         JOIN Vlucht v
         ON v.vluchtnummer = p.vluchtnummer
        JOIN Maatschappij m
        on m.maatschappijcode = v.maatschappijcode
        JOIN Luchthaven l
        on l.luchthavencode = v.bestemming
    where passagiernummer = :passagiernummer 
    and passagiernummer not in ( 
        select passagiernummer
    FROM BagageObject 
    )';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['passagiernummer' => $_SESSION['passagiernummer']]);

        while ($rij = $stmt->fetch()) {

            $gegevens = '<label>' . $rij['pasnaam'] . ' - '
                . $rij['passagiernummer']
                . '<br>'
                . $rij['vluchtnummer'] . ' - '
                . $rij['maatschappij'] . ' - '
                . $rij['airport'] . ' - '
                . $rij['land']
                . '<br>'
                . $rij['vertrektijd'] . '</label>';
            $_SESSION['vluchtnummer'] = $rij['vluchtnummer'];
        }

    }
}

if (isset($_POST['inchecken'])) {
    $maximaalgewicht = getmaximaalgewicht($_SESSION['vluchtnummer']);
    if (!(empty($_POST['koffersaantal'])) && is_numeric($_POST['koffersaantal'])) {
        $kofferaantal = $_POST['koffersaantal'];
    } else {
        $fouten[] = 'geen aantal';
    }

    if (!empty($_POST['gewichtkoffer']) && is_numeric($_POST['gewichtkoffer'])) {
        $gewichttotaal = $_POST['gewichtkoffer'];
    } else {
        $fouten[] = 'geen max';
    }
    $nieuwgewicht = $maximaalgewicht['inchecktgewicht'] + floatval($maximaalgewicht);

    if (floatval($gewichttotaal) <= $maximaalgewicht['max_gewicht_pp'] && $nieuwgewicht < floatval($maximaalgewicht['max_totaalgewicht'])) {
        $gewichtperkoffer = $gewichttotaal / $kofferaantal;
    } else {
        $fouten[] = 'gewicht kan niet';
    }

    if (count($fouten) > 0) {
        echo '<ul>';
        foreach ($fouten as $fout) {
            echo '<li>' . $fout . '</li>';
        }
        echo '</ul>';
    } else {
        $conn = maakVerbinding();
        for ($i = 0; $i < $kofferaantal; $i++) {
            $sql = '
            INSERT INTO BagageObject
            VALUES (:passagiernummer, :objectvolgnummer, :gewicht)';
            $stmt = $conn->prepare($sql);
            $succes = $stmt->execute([
                'passagiernummer' => $_SESSION['passagiernummer'],
                'objectvolgnummer' => $i,
                'gewicht' => $gewichtperkoffer
            ]);
        }

        if ($succes) {
            $melding = 'Gegevens zijn opgeslagen in de database.';
        } else {
            $melding = 'Er ging iets fout bij het opslaan.';
        }
    }
}

?>
<?php
if ($_SESSION['passagiernummer'] == 0 || empty($gegevens)) { ?>
    <form action="" method="post">
        <h1>bagage inchecken medewerker</h1>
        <label>
            passagiernummer:
            <input type="number" name="passagiernummer" required>
        </label>
        <label>
            inchecken:
            <input type="submit" name="passagiersnummerinchecken" value="passagiersnummerinchecken">
        </label>
    </form>
<?php } else {
    ?>
    <form action="" method="post">
        <h1>bagage inchecken medewerker</h1>
        <?= $gegevens ?>
        <label>
            aantal koffers:
            <input type="number" name="koffersaantal" min="1" max="3" required>
        </label>
        <label>
            gewicht totaal:
            <input type="number" name="gewichtkoffer" required>
        </label>
        <label>
            inchecken:
            <input type="submit" name="inchecken" value="inchecken">
        </label>
    </form>
<?php
}
include("./components/footer.html");
?>