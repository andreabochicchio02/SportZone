<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sport Zone - Pannello</title>
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/x-icon" href="../img/logo_senza_sfondo.png">

    <link rel="stylesheet" href="../css/pannello.css">
</head>

<body>

    <?php
        require_once "classDB.php";         /*Classe per connettersi al DB*/

        $connection = new cncDB();
        $pdo = $connection->getPDO();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id'])) {
            header("Location: login.php");
        }
    ?>

    <header class="bar">
        <img src="../img/insegna_senza_sfondo.png" alt="Logo">

        <!--Benvenuto e COOKIE-->
        <section id="info">
        <?php

            if (isset($_COOKIE[$_SESSION['id']."_accesso"])) {
                echo "<h1> BENTORNATO " . $_SESSION['id'] . "</h1>";
                echo "<h3> Ultimo acccesso " . $_COOKIE[$_SESSION['id']."_accesso"] . "</h3>";
            } else {
                echo "<h1> BENVENUTO " . $_SESSION['id'] . "</h1>";
            }

            $currentDatetime = date('d-m-Y H:i', time());
            $scadenza = time() + (10 * 365 * 24 * 60 * 60);

            //name, value, scadenza, path, domain, secure, httponly
            setcookie($_SESSION['id']."_accesso", $currentDatetime, $scadenza, "/", "", true, true);
        ?>
        </section>

        <button class="bottone" onclick="window.location.href='logout.php'">
            <img src="../img/user.png" alt="Profilo">
            Logout
        </button>
    </header>

    <section id="inizio">
        <h2>I tuoi tornei: </h2>
        <button class="bottone" onclick="window.location.href='crea_torneo.php'">
                <img src="../img/plus.png" alt="Profilo">
                Aggiungi Torneo
        </button>
    </section>
    
    <div>
    <?php 
        $ris = $connection->getTornei();

        foreach($ris as $torneo){
            echo "<a href='crea_match.php?torneo=" . urlencode($torneo['Nome']) . "' class='torneo'>";          /*urlencode per la codifica per url*/
            
            switch($torneo['Sport']){
                case 'Calcio': echo "<div class='intestazione'><img src='../img/football.png' alt='calcio'>"; break;
                case 'Pallavolo': echo "<div class='intestazione'><img src='../img/volleyball.png' alt='pallavolo'>"; break;
                case 'Tennis': echo "<div class='intestazione'><img src='../img/tennis.png' alt='tennis'>"; break;
                case 'Basket': echo "<div class='intestazione'><img src='../img/basketball.png' alt='basket'>"; break;
            }

            echo "<h4>" . $torneo['Nome'] . "</h4></div>";
            echo "<span> Luogo: " . (!empty($torneo['Luogo']) ? $torneo['Luogo'] : " nd ") . "</span>";


            $timestamp1 = strtotime($torneo['DataInizio']);                 //Secondi trascorsi dal 1 gennaio 1970
            $data1 = date("d-m-Y", $timestamp1);
            echo "<span> Data inizio: " . (($torneo['DataInizio'] !=='0000-00-00 00:00:00') ?  $data1 : 'nd') . "</span>";


            $timestamp2 = strtotime($torneo['DataFine']);                   //Secondi trascorsi dal 1 gennaio 1970
            $data2 = date("d-m-Y", $timestamp2);
            echo "<span> Data Fine: " . (($torneo['DataFine'] !=='0000-00-00 00:00:00') ?  $data2 : 'nd') . "</span>";

            echo "</a>";
        }
    ?>
    </div>

    <?php
        $connection->close();
        $pdo = null;
    ?>
</body>
</html>