<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sport Zone - Crea</title>
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/x-icon" href="../img/logo_senza_sfondo.png">

    <link rel="stylesheet" href="../css/crea.css">
    <script src="../js/crea_torneo.js"></script>
</head>

<body onload="start()">
    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*Controllo se ha fatto login*/
        if (!isset($_SESSION['id'])) {
            header("Location: login.php");
        }
    ?>
    
    <header class="bar">
        <img src="../img/insegna_senza_sfondo.png" alt="Logo">

        <div class="guida" id="crea">
            <p>Crea</p>
            <img src="../img/partecipanti.png" alt="Partecipanti">
            <img src="../img/match.png" alt="Match">
        </div>
    </header>

    <form id="container">
        <h2>Inserisci le informazioni generali del tuo torneo</h2>

        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" placeholder="Torneo UniPi">
        
        <label for="sport">Sport</label>
        <select name="choices" id="sport">
            <option disabled selected value="" id="placeholder">Seleziona uno sport</option>
            <option>Calcio</option>
            <option>Tennis</option>
            <option>Basket</option>
            <option>Pallavolo</option>
        </select>

        <label>Tipologia</label>
        <div id="scelta">
            <input type="radio" id="eliminazione2" name="tipo" value="eliminazione2">
            <label for="eliminazione2">Eliminazione doppia sfida</label> 
            <button class="info" onmouseover="mostra(0)" onmouseout="nascondi(0)"> 
                <img src="../img/info.png" alt="Info"> </button>
            <span class="hide" id="info0">Eliminazione dopo match di andata e ritorno</span> <br>

            <input type="radio" id="eliminazione1" name="tipo" value="eliminazione1">
            <label for="eliminazione1">Eliminazione diretta</label>
            <button class="info" onmouseover="mostra(1)" onmouseout="nascondi(1)"> 
                <img src="../img/info.png" alt="Info"> </button>
            <span class="hide" id="info1">Eliminazione diretta dopo un solo match</span> <br>
            
            <input type="radio" id="girone2" name="tipo" value="girone2">
            <label for="girone2">Girone doppia sfida</label>
            <button class="info" onmouseover="mostra(2)" onmouseout="nascondi(2)"> 
                <img src="../img/info.png" alt="Info"> </button>
            <span class="hide" id="info2">Partecipante affronta tutti gli altri in andata e ritorno</span> <br>
            
            <input type="radio" id="girone1" name="tipo" value="girone1">
            <label for="girone1">Girone solo andata</label>
            <button class="info" onmouseover="mostra(3)" onmouseout="nascondi(3)"> 
                <img src="../img/info.png" alt="Info"> </button>
            <span class="hide" id="info3">Partecipante affronta tutti gli altri in un solo match</span> <br>
        </div>

        <label for="luogo">Luogo</label>
        <input type="text" id="luogo" name="luogo" placeholder="Cus Pisa">

        <label for="data_inizio">Data inizio</label>
        <input type="date" id="data_inizio" name="data_inizio">

        <label for="data_fine">Data fine</label>
        <input type="date" id="data_fine" name="data_fine">

        <span class="error"></span>
        <button id="myButton">Salva</button>
    </form>
</body>
</html>