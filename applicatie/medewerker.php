<?php
include("./components/header.php"); 
naarInloggen($_SESSION['uid']);
?>
<div class="articlediv">
    <h1>medewerker</h1>
    <article>
        <article>
            <a href="bagage_inchecken.php">
                <img src="images/luggage-g70559e9e0_640.jpg" alt="koffer">
                <h2>bagage inchecken</h2>
            </a>
        </article>
        <article>
            <a href="vluchtgegevens_medewerker.php">
                <img src="images/airport-g7cf5ac3ef_640.jpg" alt="vluchtgegevens">
                <h2>vluchtgegevens bekijken</h2>
            </a>

        </article>
        <article>
            <a href="vlucht_invoeren.php">
                <img src="images/3840x2160a.jpg" alt="koffer">
                <h2>vlucht toevoegen</h2>
            </a>
        </article>
        <article>
            <a href="passagier_toevoegen.php">
                <img src="images/silhouette-g39fec6e65_640.jpg" alt="passagier">
                <h2>passagier toevoegen</h2>
            </a>
        </article>
    </article>

</div>
<?php
        include("./components/footer.html");
        ?>