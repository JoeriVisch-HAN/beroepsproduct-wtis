<?php
include("./components/header.php"); 
require_once('db_connectie.php');

function getBestemming()
{
    $conn = maakVerbinding();
    $sql = 'select * from Luchthaven';
    $stmt = $conn->query($sql);
    $opties = ' ';
    while ($rij = $stmt->fetch()) {
        $opties .= '<option value="' . $rij['luchthavencode'] . '">' . $rij['naam'] . ' - ' . $rij['land'] . '</option>';
    }
    return $opties;
}

function getGateCode()
{
    $conn = maakVerbinding();
    $sql = 'select * from Gate';
    $stmt = $conn->query($sql);
    $opties = ' ';
    while ($rij = $stmt->fetch()) {
        $opties .= '<option value="' . $rij['gatecode'] . '">' . $rij['gatecode'] . '</option>';
    }
    return $opties;
}

function getMaatschappij()
{
    $conn = maakVerbinding();
    $sql = 'SELECT maatschappijcode, naam from Maatschappij';
    $stmt = $conn->query($sql);
    $opties = ' ';
    while ($rij = $stmt->fetch()) {
        $opties .= '<option value="' . $rij['maatschappijcode'] . '">' . $rij['naam'] . '</option>';
    }
    return $opties;
}

function getNieuweVluchtnummer()
{
    $conn = maakVerbinding();
    $sql = ' SELECT MAX(vluchtnummer)+1 as vluchtnummer
    FROM Vlucht';
    $stmt = $conn->query($sql);

    while ($id = $stmt->fetch()) {
        $waarde = intval($id['vluchtnummer']);
    }
    return $waarde;
}

$conn = maakVerbinding();
$vluchtnummer = getNieuweVluchtnummer();
$bestemming = '';
$gatecode = '';
$max_aantal = 0;
$max_gewicht_pp = 0;
$max_totaalgewicht = 0;
$vertrektijd = date_create('now');
$maatschappijcode = '';
$fouten[] = '';
$melding = ' ';
$foutmelding = '';
if (isset($_POST['submit'])) {

    if (!empty($_POST['bestemming'])) {
        $bestemming = $_POST['bestemming'];
    } else {
        $fouten[] = 'Bestemming onbekend';
    }

    if (!empty($_POST['gatecode'])) {
        $gatecode = $_POST['gatecode'];
    } else {
        $fouten[] = 'gatecode onbekend';
    }

    if (!empty($_POST['max_aantal']) && is_numeric($_POST['max_aantal'])) {
        $max_aantal = $_POST['max_aantal'];
    } else {
        $fouten[] = 'maximaal aantal onbekend';
    }
    if (!empty($_POST['max_gewicht_pp']) && is_numeric($_POST['max_gewicht_pp'])) {
        $max_gewicht_pp = $_POST['max_gewicht_pp'];
    } else {
        $fouten[] = 'max_gewicht_pp onbekend';
    }

    if (!empty($_POST['vertrektijd'])) {
        $vertrektijd = date_create($_POST['vertrektijd']);
        $vertrektijd = $vertrektijd->format('Y-m-d H:i:s');
        $nu = date_create('now');
        $nu = $nu->format('Y-m-d H:i:s');
        if ($nu > $vertrektijd) {
            $fouten[] = 'vertrektijd geen goede tijd';
        }
    } else {
        $fouten[] = 'vertrektijd onbekend';
    }

    if (!empty($_POST['maatschappijcode'])) {
        $maatschappijcode = $_POST['maatschappijcode'];
    } else {
        $fouten[] = 'maatschappijcode onbekend';
    }

    if (count($fouten) > 1) {

        $foutmelding = '<ul>';
        foreach ($fouten as $fout) {
            $foutmelding .= '<li>' . $fout . '</li>';
        }
        $foutmelding .= '</ul>';
    } else {
        // Insert query
        $sql = '
        insert into Vlucht (vluchtnummer, bestemming, gatecode, max_aantal, max_gewicht_pp, max_totaalgewicht, vertrektijd, maatschappijcode)
        VALUES(:vluchtnummer, :bestemming, :gatecode, :max_aantal, :max_gewicht_pp, :max_totaalgewicht, :vertrektijd, :maatschappijcode)';
        $stmt = $conn->prepare($sql);
        $succes = $stmt->execute([
            'vluchtnummer' => $vluchtnummer,
            'bestemming' => $bestemming,
            'gatecode' => $gatecode,
            'max_aantal' => $max_aantal,
            'max_gewicht_pp' => $max_gewicht_pp,
            'max_totaalgewicht' => $max_totaalgewicht,
            'vertrektijd' => $vertrektijd,
            'maatschappijcode' => $maatschappijcode
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
    <h1>vlucht toevoegen</h1>
    <p>Maak hier een nieuwe vlucht aan.</p>
    <label>
        bestemming:
        <select name="bestemming">
            <?= getBestemming() ?>
        </select>
    </label>
    <label>
        gatecode:
        <select name="gatecode">
            <?= getGateCode() ?>
        </select>
    </label>

    <label>
        maximaal aantal personen:
        <input type="number" min="0" max="999" name="max_aantal" required oninput="this.value = Math.abs(this.value)">
    </label>
    <label>
        maximaal gewicht per persoon:
        <!-- bron oninput: https://stackoverflow.com/questions/7372067/is-there-any-way-to-prevent-input-type-number-getting-negative-values -->
        <input type="number" min="0" name="max_gewicht_pp" oninput="this.value = Math.abs(this.value)">
    </label>
    <label>
        maximaal totaalgewicht
        <input type="number" min="0" name="max_totaalgewicht" required oninput="this.value = Math.abs(this.value)">
    </label>
    <label>
        vertrektijd:
        <input type="datetime-local" required name="vertrektijd">
    </label>
    <label>
        maatschappijcode:
        <select name="maatschappijcode">
            <?= getMaatschappij() ?>
        </select>
    </label>
    <label> wissen:
        <input type="reset" id="reset" name="reset" value="wissen">
    </label>
    <label>
        toevoegen:
        <input type="submit" name="submit" value="submit">
    </label>
    <label>
        <?= $melding ?>
        <?= $foutmelding ?>
    </label>
</form>

<?php
include("./components/footer.html");
?>