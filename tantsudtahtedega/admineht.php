<?php
require_once('conf.php');


if (isset($_REQUEST['punkt0'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET punktid=0 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt0']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}


if (isset($_REQUEST['peitmine'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET avalik=0 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['peitmine']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}


if (isset($_REQUEST['naitamine'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET avalik=1 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['naitamine']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}


if (isset($_REQUEST["kustutusid"])) {
    global $yhendus;
    $kask = $yhendus->prepare("DELETE FROM tantsud WHERE id=?");
    $kask->bind_param("s", $_REQUEST['kustutusid']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}


if (isset($_REQUEST['komment0'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET kommentaarid="" WHERE id=?');
    $kask->bind_param("s", $_REQUEST['komment0']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv21 tantsud</title>
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
            Kustutamine
        </th>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid
        </th>
        <th>
            Kommentaarid / kommentaari kustutamine
        </th>
        <th>
            Avalikumistamise staatus
        </th>
        <th>
            Avaliku päev
        </th>
    </tr>

    <?php
    global $yhendus;
    $kask = $yhendus->prepare('
SELECT id, tantsupaar, punktid, kommentaarid, avaliku_paev, avalik FROM tantsud');
    $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $avaliku_paev, $avalik);
    $kask->execute();
    while ($kask->fetch()) {
        echo "<tr>";
        ?>
        <td><a href="?kustutusid=<?=$id ?>"
               onclick="return confirm('Kas ikka soovid kustutada?')">Kustutada</a>
        </td>
        <?php
        $tekst = 'Näita';
        $seisund = 'naitamine';
        $kasutajatekst = 'Kasutaja ei näe';
        if ($avalik == 1) {
            $tekst = 'Peida';
            $seisund = 'peitmine';
            $kasutajatekst = 'Kasutaja näeb';
        }
        echo "<td>" . $tantsupaar . "</td>";
        echo "<td>" . $punktid . "<br><a href='?punkt0=$id'>Punktid nulliks</a></td>";
        $kommentaarid = nl2br(htmlspecialchars($kommentaarid));
        echo "<td>" . $kommentaarid . "<br><a href='?komment0=$id'>Kustuta kommenti</a></td>";

        echo "<td>$kasutajatekst<br>
            <a href='?$seisund=$id'>$tekst</a><br>
            
            
            </td>";
        echo "<td>" . $avaliku_paev . "</td>";

        echo "</tr>";
    }
    ?>
</table>
</body>
</html>
