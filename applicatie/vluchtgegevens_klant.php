<?php
include("./components/header.php");
require_once('db_connectie.php');

require_once("./components/vluchtgegevens.php");

$conn = maakVerbinding();
$stmt = '';
$sql = '
select v.vluchtnummer, gatecode, max_aantal, max_gewicht_pp, max_totaalgewicht, vertrektijd, l.land, l.naam as vluchthaven, m.naam as maatschappijnaam
FROM Vlucht v
INNER JOIN Luchthaven l
on l.luchthavencode = v.bestemming
INNER JOIN
Maatschappij m 
on m.maatschappijcode = v.maatschappijcode
';

if (isset($_GET['submit'])) {
    $passagiernummer = null;
    $vluchtnummer = null;

    if (!empty($_GET['vluchtnummer'])) {
        $sql .= ' WHERE v.vluchtnummer Like :vluchtnummer  ';
        $vluchtnummer = '%' . $_GET['vluchtnummer'] . '%'; 
    } else if (!empty($_GET['passagiernummer'])) {
        $sql .= ' WHERE vluchtnummer IN (
            SELECT vluchtnummer
            from Passagier
            where passagiernummer = :passagiernummer
        )  ';
        $passagiernummer =  $_GET['passagiernummer'];
    }

    $stmt = $conn->prepare($sql);
    if ($passagiernummer != null) {
        $stmt->execute(['passagiernummer' => $passagiernummer]);
    } else if($vluchtnummer != null){
        $stmt->execute(['vluchtnummer' => $vluchtnummer]);
    }
} else {
    $stmt = $conn->query($sql);
}
?>

<div class="articlediv">
    <h1>vluchtgegevens</h1>
    <form action=" " method="get">
        <label>
            passagiernummer:
            <input type="search" name="passagiernummer">
        </label>
        <label>
            vluchtnummer:
            <input type="search" name="vluchtnummer">
        </label>
        <label>
            filteren/sorteren:
            <input type="submit" name="submit" value="submit">
        </label>
    </form>
    <table>
        <tr>
            <th>
                vluchtnummer
            </th>
            <th>
                maatschappijnaam
            </th>
            <th>
                vluchthaven
            </th>
            <th>
                land
            </th>
            <th>
                vertrektijd
            </th>
            <th>
                gatecode
            </th>
            <th>
                maximaal aantal personen
            </th>
            <th>
                maximaal aantal gewicht pp
            </th>
            <th>
                maximaal aantal gewicht
            </th>
        </tr>
        <?= printTableData($stmt) ?>
    </table>
</div>
<?php
include("./components/footer.html");
?>