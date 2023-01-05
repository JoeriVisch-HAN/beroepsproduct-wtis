<?php
function printTableData($waarde)
{
    $vluchtgegevens = '';
    while ($rij = $waarde->fetch()) {
        $vluchtgegevens .= " <tr>
           <td> " . $rij['vluchtnummer'] .
            "</td> <td>" . $rij['maatschappijnaam'] .
            "</td> <td>" . $rij['vluchthaven'] .
            "</td> <td>" . $rij['land'] .
            "</td> <td>" . $rij['vertrektijd'] .
            "</td> <td>" . $rij['gatecode'] .
            "</td> <td>" . $rij['max_aantal'] .
            "</td> <td>" . $rij['max_gewicht_pp'] .
            "</td> <td>" . $rij['max_totaalgewicht'] .
            "</td> </tr>";
    }
    return $vluchtgegevens;
}
?>