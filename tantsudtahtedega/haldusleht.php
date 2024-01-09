<?php
require_once ("conf.php");
session_start();
// punktide lisamine
if(isset($_REQUEST["heatants"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud set punktid=punktid+1 where id=?");
    $kask->bind_param("i", $_REQUEST["heatants"]);
    $kask->execute();
}
if(isset($_REQUEST["heatantsu"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud set punktid=punktid-1 where id=?");
    $kask->bind_param("i", $_REQUEST["heatantsu"]);
    $kask->execute();
}
if(isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"]) && isAdmin()){

    global $yhendus;
    $kask=$yhendus->prepare("Insert into tantsud(tantsupaar, ava_paev) VALUES(?, NOW()) ");
    $kask->bind_param("s", $_REQUEST["paarinimi"]);
    $kask->execute();
    //header("Localhost: $_SERVER[PHP_SELF]");
    //$yhendus->close();
}
if(isset($_REQUEST["komment"])){
    if(!empty($_REQUEST["uuskomment"])){
        global $yhendus;
        $kask = $yhendus->prepare("UPDATE tantsud SET komentaarid=CONCAT(komentaarid, ?) WHERE id=?");
        $kommentplus=$_REQUEST["uuskomment"]."\n";
        $kask->bind_param("si", $kommentplus, $_REQUEST["komment"]);
        $kask->execute();
        header("Location: $_SERVER[PHP_SELF]");
        $yhendus->close();
        //exit();
    }
}

function isAdmin(){
    return  isset ($_SESSION['onAdmin']) && $_SESSION['onAdmin'] ;
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
        width: 50%;
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

<h2> Punktide lisamine</h2>
<?php
if(isset($_SESSION['kasutaja'])){
    ?>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Paev</th>
        <th>Kommentaarid</th>

    </tr>
<?php
global $yhendus;
$kask=$yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, komentaarid FROM tantsud WHERE avalik=1");
$kask->bind_result($id, $tantsupaar, $punktid, $paev, $komment);
$kask->execute();
while($kask->fetch()){
    echo "<tr>";
    $tantsupaar=htmlspecialchars($tantsupaar);
    echo "<td>".$tantsupaar."</td>";
    echo "<td>".$punktid."</td>";
    echo "<td>".$paev."</td>";
    echo "<td>".nl2br(htmlspecialchars($komment))."</td>";

    echo "<td>
<form action='?'>
        <input type='hidden'  value='$id' name='komment'>
        <input type='text' name='uuskomment' id='uuskomment'>
        <input type='submit' value='OK'>
</form>
        ";
    echo "<td><a href='?heatants=$id'>Lisa +1punkt</a></td>";

    echo "</tr>";
}}
?>
    <?php
    if(isAdmin()){ ?>
    <form action="?">
        <label for="paarinimi">Lisa uus paar</label>
        <input type="text" name="paarinimi" id="paarinimi">
        <input type="submit" value="Lisa paar">
    </form>
        <?php }  ?>
</table>
</body>
</html>
<?php
