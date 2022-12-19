<?php
include("./components/header.html");
include('db_connectie.php');
$conn = maakVerbinding();

$sql = 'select * from Gate';
$stmt = $conn->query($sql);

?>

<div class="fotohome">
    <img src="images/3840x2160a.jpg" alt="vliegtuig">
    <h1>Gelre Airport</h1>
    <p>Het mooiste vliegveld uit gelderland</p>
</div>

<?php
include("./components/footer.html");
?>