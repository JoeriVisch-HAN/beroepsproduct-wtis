<?php
include("./components/header.php"); 
naarInloggen($_SESSION['uid']);
require_once('db_connectie.php');
require_once("./components/vluchtgegevens.php");
$conn = maakVerbinding();
$sql = '
select v.vluchtnummer, gatecode, max_aantal, max_gewicht_pp, max_totaalgewicht, vertrektijd, l.land, l.naam as vluchthaven, m.naam as maatschappijnaam
FROM Vlucht v
INNER JOIN Luchthaven l
on l.luchthavencode = v.bestemming
INNER JOIN
Maatschappij m 
on m.maatschappijcode = v.maatschappijcode
';

if (isset($_GET['submit']) && (!empty($_GET['vluchtnummer']) || (!empty($_GET['sorteren']) && $_GET['sorteren'] != 'geen'))) {
    $vluchtnummer = null;
    $sorteren = null;

    if (!empty($_GET['vluchtnummer'])) {
        $sql .= ' WHERE vluchtnummer Like :vluchtnummer  ';
        $vluchtnummer = '%' . $_GET['vluchtnummer'] . '%';
        
    }

    if (!empty($_GET['sorteren'])) {
        $sorteren = $_GET['sorteren'];
        if ($sorteren == 'vertrektijd') {
            $sql .= ' order by vertrektijd asc';
        } else if ($sorteren == 'bestemming') {
            $sql .= ' order by vluchthaven asc';
        } else if ($sorteren == 'vertrektijd bestemming') {
            $sql .= ' order by vluchthaven, bestemming asc';
        }
    }

    $stmt = $conn->prepare($sql);
    if ($sorteren != null && $vluchtnummer == null) {
        $stmt = $conn->query($sql);
    } else if ($vluchtnummer != null) {
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
            sorteren
            <select name="sorteren">
                <option value="geen">geen</option>
                <option value="vertrektijd">tijd</option>
                <option value="bestemming">luchthaven</option>
                <option value="vertrektijd bestemming">tijd en luchthaven</option>
            </select>
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