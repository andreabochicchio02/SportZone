<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sport Zone - Crea</title>
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/x-icon" href="../img/logo_senza_sfondo.png">
    
    <link rel="stylesheet" href="../css/crea_match.css">
    <script src="../js/crea_match.js"></script>
</head>
<body onload="start()">

    <?php
        require_once "classDB.php";

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //Controllo se ha fatto login
        if (!isset($_SESSION['id'])) {
            header("Location: login.php");
        }

        $connection = new cncDB();
        $pdo = $connection->getPDO();

        try{
            if(!isset($_SERVER['HTTP_REFERER'])){
                throw new Exception("Error");
            }

            if((strpos($_SERVER['HTTP_REFERER'], "php/pannello.php") !== false)) {
                $_SESSION['torneo'] = $_GET['torneo'];
            }
        } 
        catch(Exception $e){
            header("Location: ../HTML/404.html");
        }
    ?>

    <!--Barra-->
    <header class="bar">
        <img src="../img/insegna_senza_sfondo.png" alt="Logo">

        <div id="guida">
        <img src="../img/generale.png" alt="Generale"/>
            <img src="../img/partecipanti.png" alt="Partecipanti"/>
            <p>Match</p>
        </div>

        <button id="myButton">
            <img src="../img/menu.png" alt="Profilo">
        </button>
        <nav id='menu'>
            <ul>
                <li  onclick="popup_partite()" id="apri">Aggiungi Partita</li>
                <li><a href="crea_partecipanti.php">Aggiungi Partecipanti</a></li>
                <li><a href="pannello.php">Torna al Pannello</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    

    <?php
        $sport = $connection->getSport();

        echo "<section id='titolo'>";
        switch($sport) {     /*Inserisco immagine dello sport*/
            case 'Calcio':
                echo "<img src='../img/football.png' alt='calcio'>";
                break;
            case 'Pallavolo':
                echo "<img src='../img/volleyball.png' alt='pallavolo'>";
                break;
            case 'Tennis':
                echo "<img src='../img/tennis.png' alt='tennis'>";
                break;
            case 'Basket':
                echo "<img src='../img/basketball.png' alt='basket'>";
                break;
        }
        
        echo "<h1>" . strtoupper($_SESSION['torneo']) . "</h1></section>";
        
    ?>
    
    <!-- Se non sono presenti partite -->
    <?php
        $partite = $connection->getPartite();
        
        if(count($partite) === 0){
            echo "<section id='vuoto'>";
            echo "<h1>Non sono state ancora organizzate partite per questo torneo. Per creare nuovi match vai sul menu in alto a destra 
                oppure clicca sul bottone 'Aggiungi Partita'</h1>";
            echo "<button class='open' onclick='popup_partite()'> <img src='../img/plus.png' alt='nuova'> Aggiungi Partita </button>";
            echo "</section>";
        }
    ?>

    <!-- PopUp per aggiungere una partita -->
    <section class="popup">
        <form id="form_partita">
            <h2>Informazioni partita</h2>
            
            <label for="partecipante1">Seleziona partecipante 1:</label>
            <select id="partecipante1" name="partecipante1">
                <option disabled selected>Seleziona partecipante 1</option>
                <?php
                $partecipanti = $connection->getPartecipanti();

                foreach ($partecipanti as $partecipante) {
                    echo "<option value='" . $partecipante['Id'] . "'>" . $partecipante['Nome'] . "</option>";
                }
                ?>
            </select>

            <label for="partecipante2">Seleziona partecipante 2:</label>
            <select id="partecipante2" name="partecipante2">
                <option disabled selected>Seleziona partecipante 2</option>
                <?php
                $partecipanti = $connection->getPartecipanti();

                foreach ($partecipanti as $partecipante) {
                    echo "<option value='" . $partecipante['Id'] . "'>" . $partecipante['Nome'] . "</option>";
                }
                ?>
            </select>
            
            <label for="luogo">Luogo</label>
            <input type="text" id="luogo" name="luogo" placeholder="Cus Pisa">

            <label for="data">Data</label>
            <input type="date" id="data" name="data">
            <label for="ora">Ora</label>
            <input type="time" id="ora" name="ora">


            <?php
                $turni2 = array("Finale", "Semifinale andata", "Semifinale ritorno", "Quarti di finale andata", 
                            "Quarti di finale ritorno", "Ottavi di finale andata", "Ottavi di finale ritorno", 
                            "Sedicesimi di finale andata", "Sedicesimi di finale ritorno", "Trentaduesimi di finale andata", 
                            "Trentaduesimi di finale ritorno", "Sassantaquattresimi di andata", "Sassantaquattresimi di ritorno");
                $turni1 = array("Finale", "Semifinale", "Quarti di finale", "Ottavi di finale", "Sedicesimi di finale", 
                            "Trentaduesimi di finale", "Sassantaquattresimi  di finale");
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
                switch ($tipo) {
                case 'eliminazione2':                           //Caso eliminazione andata e ritorno
                    echo "<label for='turno'>Seleziona turno:</label>";
                    echo "<select id='turno' name='turno'>";
                    for ($i = 0; $i < $numero; $i++) {
                        echo "<option value='$i'>" . $turni2[$i] . "</option>";
                    }
                    echo "</select>";
                    break;  
                case 'eliminazione1':                           //Caso eliminazione diretta
                    echo "<label for='turno'>Seleziona turno:</label>";
                    echo "<select id='turno' name='turno'>";
                    for ($i = 0; $i < (($numero+1)/2); $i++) {
                        echo "<option value='$i'>" . $turni1[$i] . "</option>";
                    }
                    echo "</select>";
                    break;
                case 'girone2':                                 //Girone andata e ritorno
                    echo "<label for='turno'>Seleziona turno:</label>";
                    echo "<select id='turno' name='turno'>";
                    for ($i = 1; $i <= $numero; $i++) {
                        if($i <= $numero/2){
                            echo "<option value='$i'>" . $i . " Giornata di andata</option>";
                        } else {
                            echo "<option value='$i'>" . ($i-$numero/2) . " Giornata di ritorno</option>";
                        }
                    }
                    echo "</select>";
                    break;
                case 'girone1':                                 //Girone solo andata
                        echo "<label for='turno'>Seleziona turno:</label>";
                        echo "<select id='turno' name='turno'>";
                        for ($i = 1; $i <= ($numero/2); $i++) {
                            echo "<option value='$i'>" . $i. " Giornata</option>";
                        }
                        echo "</select>";
                    break;
                default:
                    break;
                }

                ?>

            <button class="chiudi"> 
                <img src="../img/close.png" alt="chiudi"> 
            </button>

            <span id="error_partita"></span>
            <button class="invio">Salva</button>
        </form>
    </section>

    <!-- Elemento partita -->
    <?php 
        if(count($partite) !== 0){
            $match = $connection->getPartiteTorneo();
            $turno = '';

            echo "<section class='risultati'>";
            foreach($match as $partita){

                //FORMATTO L'ORARIO
                $Timestamp = strtotime($partita['Data']);                // Converti la data in timestamp Unix
                $data = date("d-m-Y H:i", $Timestamp);         // Formatta la data


                //Intestazione per ogni turno
                if($partita['Turno'] !== $turno){       //Controllo quando devo fare una nuova riga
                    if($turno !== ''){                  //Chiudo <section> ogni turno
                        echo "</section><section class='risultati'>" ;   
                    }

                    echo "<h3>";
                    
                        if($connection->getTipologia() === "girone1"){
                            echo $partita['Turno'] . " Giornata";
                        }elseif($connection->getTipologia() === "girone2"){
                            if($partita['Turno'] <= ($numero/2)){                                //Riprendo la variabile $numero creata prima
                                echo $partita['Turno']. " Giornata di andata";
                            } else {
                                echo ($partita['Turno'] - $numero/2) . " Giornata di ritorno";
                            }
                        }elseif($connection->getTipologia() === "eliminazione2"){
                            echo $turni2[$partita['Turno']];        //Riprendo il vettore di prima    
                        }else{
                            echo $turni1[$partita['Turno']];        //Riprendo il vettore di prima
                        }

                    echo "</h3>";
                    
                    $turno = $partita['Turno'];
                }
                echo "<article class='match'>";
                echo "<div class='intestazione'><h4 class='team'>" . $partita['Nome1'] . "</h4>"; 


                $p1 = $connection->getId($partita['Nome1']);
                $p2 = $connection->getId($partita['Nome2']);
                
                /*BOTTONE AGGIUNGI RISULTATO*/
                if ($Timestamp > strtotime("now")){
                    echo "<h4 class='ris'> n.a. </h4>";
                }
                elseif(!isset($partita['Punti1']) || !isset($partita['Punti2'])){
                    echo "<button class='open' onclick=\"popup_risultati('" . $partita['Nome1'] . "', '" . $partita['Nome2'] . "')\"> 
                        <img src='../img/plus.png' alt='risultato'> Aggiungi Risultato </button>";

                } else {
                    echo "<h4 class='ris'>" . $partita['Punti1'] . " - " . $partita['Punti2'] . "</h4>";
                }

                echo "<h4 class='team'>" . $partita['Nome2'] . "</h4></div>";
                echo "<div class='where'> <img src='../img/location.png' alt='Luogo'>";
                echo "<h5>" . (!empty($partita['Luogo']) ? $partita['Luogo'] : " nd ") . "</h5> </div>";
                echo "<div class='when'> <img src='../img/calendar.png' alt='Data'>";
                echo "<h5>" . (($partita['Data'] !=='0000-00-00 00:00:00') ?  $data : ' nd') . "</h5> </div>";
                echo "</article>";
            }
            echo "</section>";
        }
    ?>


    <!-- PopUp per aggiungere risultato -->
    <section class="popup">
        <form id="form_risultato">
            <?php
                $sport = $connection->getSport();
                switch($sport){
                    case 'Tennis':
                    case 'Pallavolo': echo "<h2>Inserisci i set vinti</h2>"; break;
                    case 'Calcio': echo "<h2>Inserisci i goal realizzati</h2>"; break;
                    case 'Basket': echo "<h2>Inserisci i punti segnati</h2>"; break;
                }
            ?>
            
            <label id="p1" for="punti1">Partecipante1</label>
            <input min="0" type="number" id="punti1" name="punti1">

            <label id="p2" for="punti2">Partecipante2</label>
            <input min="0" type="number" id="punti2" name="punti2">

            <button class="chiudi"> 
                <img src="../img/close.png" alt="chiudi"> 
            </button>

            <span id="error_ris"></span>

            <button class="invio">Salva</button>
        </form>
    </section>


    <?php
        $connection->close();
        $pdo = null;
    ?>
</body>
</html>