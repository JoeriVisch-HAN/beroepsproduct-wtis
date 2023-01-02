<?php
session_start();
if (!isset($_SESSION['uid'])) {
  $_SESSION['uid'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/style.css">
  <title>Gelre Airport</title>
</head>

<body>
  <header></header>
  <nav class="navigatie">
    <ul>
      <li><a href="index.php">Home</a></li>
      
        <li><a href="passagier.php">overzicht passagier</a></li>
        
        <?php if($_SESSION['uid'] != null){ ?>
      <li><a href="medewerker.php">overzicht medewerker</a>
      </li>
      <?php } ?>
      <?php if (!$_SESSION['uid']) { ?>
        <li><a href="inloggen.php">inloggen</a></li>
        <?php } else { ?>
        <li><a href="logout.php">uitloggen</a> </li>
        <?php } ?>
    </ul>
  </nav>
  <main>