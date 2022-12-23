<?php
include("./components/header.html");
require_once('db_connectie.php');

function getpassagiersnummer()
{
    $conn = maakVerbinding();
    $sql = ' select passagiernummer, naam
    from Passagier';
    $stmt = $conn->query($sql);
    
    while($rij = $stmt->fetch()){
        $waarde .= '<option value="' . $rij['passagiernummer'] . '">' . $rij['naam'] . '</option>';
    }
    return $waarde;
}
?>
<form action=" " method="post">
    <h1>bagage inchecken medewerker</h1>
    <p>Check hier alvast je bagage in!</p>
    <label>
        passagiernummer:
        <select name="passagiersnummer">
            <?= getpassagiersnummer() ?>
        </select>
    </label>
    <label>
        vluchtnummer:
        <select>
            <option>122458945</option>
            <option>43437437890</option>
        </select>
    </label>
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
include("./components/footer.html");
?>