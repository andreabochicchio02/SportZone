<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sport Zone - Torneo</title>
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/x-icon" href="../img/logo_senza_sfondo.png">

    <link rel="stylesheet" href="../css/torneo.css" >
    <script src= "../js/torneo.js" ></script>
</head>
<body onload="start()">

    <?php
        require_once "classDB.php";
        $connection = new cncDB();
        $pdo = $connection->getPDO();

        /*Inizio una nuova sessione solo se non ci sono altre sessioni attive*/
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['torneo'] =  $_GET['torneo'];

        /*Controllo anche qui se il torneo esiste*/
        $nome = $connection->getTorneo();
        if(count($nome) === 0){
            header("Location: ../index.php");
        }
    ?>

    <header class="bar">
        <img src="../img/insegna_senza_sfondo.png" alt="Logo">

        <?php
        $sport = $connection->getSport();

        switch($sport){
            case 'Calcio': echo "<section id='titolo'><img src='../img/football.png' alt='calcio'>"; break;
            case 'Pallavolo': echo "<section id='titolo'><img src='../img/volleyball.png' alt='pallavolo'>"; break;
            case 'Tennis': echo "<section id='titolo'><img src='../img/tennis.png' alt='tennis'>"; break;
            case 'Basket': echo "<section id='titolo'><img src='../img/basketball.png' alt='basket'>"; break;
        }
        ?> 
        <?php echo"<h1>" . $_SESSION['torneo'] . "</h1></section>" ?> 

        <button id="myButton" onclick="window.location.href='login.php'">
            <img src="../img/user.png" alt="Profilo">
            Accedi
        </button>
    </header>
    
    <?php
        $partite = $connection->getPartite();
        if(count($partite) === 0){
            /*Se non sono presenti partite*/
            echo "<h2>Non sono state ancora organizzate partite per questo torneo</h2>";


        } else {
            echo "<button id='mod'>Visualizza calendario</button>";

            /* Crea l'elemento partita*/
            $turni2 = array("Finale", "Semifinale andata", "Semifinale ritorno", "Quarti di finale andata", 
            "Quarti di finale ritorno", "Ottavi di finale andata", "Ottavi di finale ritorno", 
            "Sedicesimi di finale andata", "Sedicesimi di finale ritorno", "Trentaduesimi di finale andata", 
            "Trentaduesimi di finale ritorno", "Sassantaquattresimi di andata", "Sassantaquattresimi di ritorno");

            $turni1 = array("Finale", "Semifinale", "Quarti di finale", "Ottavi di finale", "Sedicesimi di finale", 
                    "Trentaduesimi di finale", "Sassantaquattresimi  di finale");
            
            /*In base alla tipoligia di torneo calcolo in numero di giornate o di eliminazioni*/
            $tipo = $connection->getTipologia();
            $partecipanti = count($connection->getPartecipanti());
            if($tipo==="eliminazione2" || $tipo==="eliminazione1"){
                if($partecipanti>64) $numero = 13;
                elseif($partecipanti>32) $numero = 11;
                elseif($partecipanti>16) $numero = 9;
                elseif($partecipanti>8) $numero = 7;
                elseif($partecipanti>4) $numero = 5;  
                elseif($partecipanti>2) $numero = 3;
                else $numero = 1;
            } else {
                $numero = ($partecipanti-1)*2;
            }

            $turno = '';

            $match = $connection->getPartiteTorneo();

            /*Ci sono tutte le partite*/
            echo "<div id='elenco_partite'><section class='risultati'>";
            foreach($match as $partita){
                /*FORMATTO L'ORARIO*/
                $Timestamp = strtotime($partita['Data']);                //Secondi trascorsi dal 1 gennaio 1970
                $data = date("d-m-Y H:i", $Timestamp);

                /*Inizio turno*/
                if($partita['Turno'] !== $turno){
                    if($turno !== ''){                  /*Chiudo <section> ogni turno*/
                        echo "</section><section class='risultati'>" ;   
                    }

                    echo "<h3>";
                    
                        if($connection->getTipologia() === "girone1"){
                            echo $partita['Turno'] . " Giornata";
                        }elseif($connection->getTipologia() === "girone2"){
                            if($partita['Turno'] <= ($numero/2)){
                                echo $partita['Turno']. " Giornata di andata";
                            } else {
                                echo ($partita['Turno'] - $numero/2) . " Giornata di ritorno";
                            }
                        }elseif($connection->getTipologia() === "eliminazione2"){
                            echo $turni2[$partita['Turno']];        /*Riprendo il vettore di prima*/     
                        }else{
                            echo $turni1[$partita['Turno']];        /*Riprendo il vettore di prima*/ 
                        }

                    echo "</h3>";
                    
                    $turno = $partita['Turno'];
                }

                /*Match*/
                echo "<article class='match'>";
                echo "<div class='intestazione'><h4 class='team'>" . $partita['Nome1'] . "</h4>"; 

                if ($Timestamp > strtotime("now")){
                    echo "<h4 class='ris_nd'>n.a.</h4>";
                }
                elseif(!isset($partita['Punti1']) || !isset($partita['Punti2'])){
                    echo "<h4 class='ris_nd'>nd</h4>";
                } else {
                    echo "<h4 class='ris'>" . $partita['Punti1'] . " - " . $partita['Punti2'] . "</h4>";
                }

                echo "<h5 class='team'>" . $partita['Nome2'] . "</h5></div>";
                
                echo "<div class='where'> <img src='../img/location.png' alt='Luogo'>";
                echo "<h5>" . (!empty($partita['Luogo']) ? $partita['Luogo'] : " nd ") . "</h5> </div>";
                echo "<div class='when'> <img src='../img/calendar.png' alt='Data'>";
                echo "<h5>" . (($partita['Data'] !=='0000-00-00 00:00:00') ?  $data : ' nd') . "</h5> </div>";
                echo "</article>";
            }
            echo "</section></div>";
        }
    ?>

    <!--CALENDARIO-->
    <div class="hide" id="calendario_partite">
        <table id="tab_calendario">
            <thead id="intestazione">

            </thead>
            <tbody id="calendario">

            </tbody>
        </table>

        <button id="precedente">Mese Precedente</button>
        <button id="successivo">Mese Successivo</button>
    </div>
    <p id="error"></p>

        
    <!--CLASSIFICA-->
    <?php
        $mod = $connection->getTipologia();

        $partecipante = $connection->classifica();
        echo "<table id='classifica'><caption>";
        if($mod === "eliminazione1" || $mod === "eliminazione2")
            echo "STATISTICHE</caption>";
        else 
            echo "CLASSIFICA</caption>";
        echo "<thead><tr>
            <th scope='col'>Pos</th>
            <th scope='col'>Partecipante</th>
            <th scope='col'>V</th>";
            if($sport==="Calcio" && $mod !== "eliminazione1") echo "<th scope='col'>P</th>";
        echo "<th scope='col'>S</th>
            </tr></thead>
            <tbody>";

        $i = 1;
        foreach($partecipante as $row){
            $id =  $connection->getId($row['Nome']);
            $tot_partite = count($connection->getPartitePartecipante($id));
            $sconfitte = $tot_partite - ($row['Vinte'] + $row['Pareggiate']);

            echo "<tr><td>" . $i . 
                "</td><td>" . $row['Nome'] .
                "</td><td>" . $row['Vinte'] . "</td>";
                if($sport==="Calcio" && $mod !== "eliminazione1") echo "<td>" . $row['Pareggiate'];
                echo "</td><td>" . $sconfitte . "</td></tr>";

                $i=$i+1;
        }
        echo "</tbody></table>"
    ?>

    <?php
        $connection->close();
        $pdo = null;
    ?>
</body>
</html>