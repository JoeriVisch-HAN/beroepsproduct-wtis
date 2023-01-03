<?php
include("./components/header.php"); 
redirect($_SESSION['uid']);
require_once('db_connectie.php');
$gegevens = '';
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
    return $waarde[0];
}

if (isset($_POST['passagiersnummerinchecken'])) {
    if (!empty($_POST['passagiernummer']) && is_numeric($_POST['passagiernummer'])) {
        $passagiernummer = getPassagier($_POST['passagiernummer']);
        $conn = maakVerbinding();
        $sql = 'select passagiernummer, p.naam as pasnaam, p.vluchtnummer, m.naam as maatschappij, vertrektijd, l.naam as airport, l.land
        from Passagier p
         JOIN Vlucht v
         ON v.vluchtnummer = p.vluchtnummer
        JOIN Maatschappij m
        on m.maatschappijcode = v.maatschappijcode
        JOIN Luchthaven l
        on l.luchthavencode = v.bestemming
    where passagiernummer = :passagiernummer';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['passagiernummer' => $passagiernummer]);
        
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
        }
    }


}

?>
<?php
if (empty($passagiernummer)) { ?>
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

<form action=" " method="post">
    <h1>bagage inchecken medewerker</h1>
    <?=$gegevens?>
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
        <input type="submit" name="inloggen" value="inchecken">
    </label>
</form>
<?php
}
include("./components/footer.html");
?>