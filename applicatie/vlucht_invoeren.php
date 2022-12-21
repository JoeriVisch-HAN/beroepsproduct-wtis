<?php
include("./components/header.html");
?>
<form action="#" method="post">
            <h1>vlucht toevoegen</h1>
            <p>Maak hier een nieuwe vlucht aan.</p>
            <label>
                vlucht van:
                <input type="text" pattern="[a-zA-Z]+" name="vluchtvanaf">
            </label>
            <label>
                vlucht naar:
                <input type="text" pattern="[a-zA-Z]+" name="vluchtnaar">
            </label>

            <label>
                vlucht vertrek dag:
                <input type="date" name="vluchtvertrek">
            </label>
            <label>
                vlucht vertrek uur:
                <input type="time" name="vluchtvertrek">
            </label>
            <label>
                vlucht aankomst dag:
                <input type="date" name="vluchtvertrek">
            </label>
            <label>
                vlucht aankomst uur:
                <input type="time" name="vluchtvertrek">
            </label>

            <label>
                toestelnumer:
                <input type="number" name="toestelnumer">
            </label>
            <label>
                aantal personen:
                <input type="number" name="aantal personen">
            </label>
            <label>
                toevoegen:
                <input type="submit" name="inloggen" value="toevoegen">
            </label>
        </form>
        <?php
        include("./components/footer.html");
        ?>       