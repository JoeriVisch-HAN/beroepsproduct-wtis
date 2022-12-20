<?php
include("./components/header.html");
require_once('db_connectie.php');
$vluchtgegevens = '';
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
$stmt = $conn->query($sql);
while ($rij = $stmt->fetch()) {
    $vluchtgegevens .= " <tr>
       <td> " . $rij['vluchtnummer'] .
        "</td> <td>" . $rij['maatschappijnaam'] .
        "</td> <td>" . $rij['vluchthaven'] .
        "</td> <td>" . $rij['vertrektijd'] .
        "</td> <td>" . $rij['gatecode'] .
        "</td> <td>" . $rij['max_aantal'] .
        "</td> <td>" . $rij['max_gewicht_pp'] .
        "</td> <td>" . $rij['max_totaalgewicht'] . 
        "</td> </tr>";
}
?>

<div class="articlediv">
    <h1>vluchtgegevens</h1>
    <form action="#" method="post">
        <label>
            sorteren
            <select>
                <option>geen</option>
                <option>tijd</option>
                <option>vluchthaven</option>
                <option>tijd en vluchthaven</option>
            </select>
        </label>
        <label>
            vluchtnummer:
            <input type="search" name="aantal personen">
        </label>
        <label>
            filteren:
            <input type="submit" name="inloggen" value="filteren">
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
      <?=$vluchtgegevens?>
    </table>
</div>
<?php
include("./components/footer.html");
?>