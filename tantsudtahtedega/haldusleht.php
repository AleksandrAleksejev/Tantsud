<?php
require_once ('conf.php');
global $yhendus;
$sorttulp = "tantsupaar";
$otsisona = "";

if (isset($_REQUEST["sorttulp"])) {
    $sorttulp = $_REQUEST["sorttulp"];
}
if (isset($_REQUEST["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}

function Sortirovka($sorttulp="tantsupaar", $otsisona=""){
    global $yhendus;

    $lubatudtulbad=array("tantsupaar", "punktid", "kommentaarid");
    if(!in_array($sorttulp, $lubatudtulbad))
    {
        return "lubamatu tulp";
    }
        if($sorttulp=="punktid")
    {
        $sorttulp="punktid";
    }
        if($sorttulp=="kommentaarid")
    {
        $sorttulp="kommentaarid";
    }
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT id, tantsupaar, punktid, kommentaarid  FROM tantsud
       WHERE avalik=1
        AND (tantsupaar LIKE '%$otsisona%' OR punktid LIKE '%$otsisona%' OR kommentaarid LIKE '%$otsisona%')
       ORDER BY $sorttulp");
    $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $sort=new stdClass();
        $sort->id=$id;
        $sort->tantsupaar=htmlspecialchars($tantsupaar);
        $sort->punktid=$punktid;
        $sort->kommentaarid=htmlspecialchars($kommentaarid);

        array_push($hoidla, $sort);
    }
    return $hoidla;
}

$tantsud = Sortirovka($sorttulp, $otsisona);


//Uue tantsupaari lisamine
if (!empty($_REQUEST['paarinimi'])) {
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO tantsud (tantsupaar, avaliku_paev) VALUES NOW()");
    $kask->bind_param("ss", $_REQUEST['paarinimi']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");

}
//kommentaaride lisamine


//punktide lisamine
if(isSet($_REQUEST['punkt'])){
    global $yhendus;
    $kask=$yhendus->prepare('
UPDATE tantsud SET punktid=punktid+1 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv22 tantsud</title>
    <link rel="stylesheet" type="text/css" href="menu.css">
    <style>
        header {
            padding: 60px;
            text-align: center;
            background: #333;
            color: white;
            font-size: 60px;
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
</head>
<body>
<header>
    <h1>Tantsud TARpv22</h1>
    <nav>
        <ul>
            <li>
                <a href="haldusleht.php">Kasutaja leht</a>
            </li>
            <li>
                <a href="admineht.php">Admin leht</a>
            </li>
        </ul>
    </nav>
</header>
<table>
    <tr>
        <th>
            <a href="haldusleht.php">Tantsupaar</a>
        </th>
        <th>
            <a href="haldusleht.php">Punktid</a>
        </th>
        <th>
            Haldus
        </th>
        <th>
            <a href="haldusleht.php">Kommentaarid</a>
        </th>
        <th>
            Kommentaarid lisamine
        </th>


    </tr>
        </form>

        <?php
        // tabeli sisu näitamine
        global $yhendus;
        $kask=$yhendus->prepare('
SELECT id, tantsupaar, punktid,kommentaarid FROM tantsud WHERE avalik=1');
        $kask->bind_result($id, $tantsupaar, $punktid,$kommentaarid);
        $kask->execute();
        foreach ($tantsud as $tantsupaar):

            echo  "<tr>";
            echo "<td>".$tantsupaar->tantsupaar."</td>";
            echo "<td>".$tantsupaar->punktid."</td>";
            echo "<td>". "<a href='?punkt=$tantsupaar->id'>Lisa 1punkt</a>" ."</td>";
            echo "<td>".nl2br($tantsupaar->kommentaarid)."</td>";
            echo "<td>
<form action='?'>
<input type='hidden' value='$tantsupaar->id' name='uuskomment'>
<input type='text' name='komment'>
<input type='submit' value='OK'>
</form>
</td>";

            ?>
        <?php endforeach; ?>
</table>

<div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <center>
        <h2>Tantsupaari lisamine</h2>
        <form action="?">
            <input type="text" placeholder="Tantsupaar" name="paarinimi">
            <br>
            <input type="submit" value="OK">

        </form>
    </center>
</div>
</body>

