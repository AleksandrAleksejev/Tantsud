<?php

require_once ("conf.php");
session_start();
// punktide lisamine
if(isset($_REQUEST["punktid0"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud set punktid=0 where id=?");
    $kask->bind_param("i", $_REQUEST["punktid0"]);
    $kask->execute();
}
// peitmine
if(isset($_REQUEST["peitmine"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud set avalik=0 where id=?");
    $kask->bind_param("i", $_REQUEST["peitmine"]);
    $kask->execute();
}
// näitmine
if(isset($_REQUEST["naitmine"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud set avalik=1 where id=?");
    $kask->bind_param("i", $_REQUEST["naitmine"]);
    $kask->execute();
}

?>
<!doctype html>
<html lang=est>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="menu.css">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tantsud tähtedege</title>
</head>
<body>
<header>
    <h1>Tantsud tähtedega</h1>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login.php">Logi sisse</a>
        <?php
    }
    ?>
</header>
<style>
    header {
        padding: 20px;
        text-align: center;
        background: #333;
        color: white;
        font-size: 30px;
    }
    nav {
        background-color: #333;
        overflow: hidden;
    }

    nav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    nav a:hover {
        background-color: #ddd;
        color: black;
    }

    nav a.active {
        background-color: #04AA6D;
        color: white;
    }
    table, th, td {
        border: 1px solid;
    }
    table {
        width: 100%;
    }
    table {
        border-collapse: collapse;
    }
    table {
        border: 1px solid;
    }
</style>
<nav>

        <a href="haldusleht.php">Kasutaja</a>
        <a href="admineht.php">Admin</a>

</nav>
<h2> Administrerimis Leht</h2>
<?php
if(isset($_SESSION['kasutaja'])){
?>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Kuupaev</th>
        <th>Komentaarid</th>
        <th>Avalik</th>

    </tr>

<?php
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id,tantsupaar, punktid, ava_paev, komentaarid, avalik FROM tantsud ");
    $kask->bind_result($id, $tantsupaar, $punktid, $paev, $komment, $avalik);
    $kask->execute();
    while ($kask->fetch()) {
        $tekst = "Naita";
        $seisund = "naitmine";
        $tekst2 = "Kasutaja ei naeb";
        if ($avalik == 1) {
            $tekst = "Peida";
            $seisund = "peitmine";
            $tekst2 = "Kasutaja ei naeb";
        }

        echo "<tr>";
        $tantsupaar = htmlspecialchars($tantsupaar);
        echo "<td>" . $tantsupaar . '</td>';
        echo "<td>" . $punktid . '</td>';
        echo "<td>" . $paev . '</td>';
        echo "<td>" . $komment . '</td>';
        echo "<td>" . $avalik . "/" . $tekst2 . "</td>";

        echo "<td><a href='?punktid0=$id'>Punktid Nulliks!</a></td>";
        echo "<td><a href='$seisund=$id'>$tekst</a></td>";
        echo "<tr>";

    }
}
?>

</table>
</body>
</html>

<!-- kasutaja 1. admin õigused: ei saa + 1 punkt ja - 1punkt ja üldse ei need lingid
kasutaja 2. opilane õigused ei näe adminleht.php
