<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sport Zone - Crea</title>
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/x-icon" href="../img/logo_senza_sfondo.png">

    <link rel="stylesheet" href="../css/crea.css">
    <script src="../js/crea_partecipanti.js"></script>
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

        <div class="guida" id="partecipanti">
            <img src="../img/generale.png" alt="Partecipanti">
            <p>Partecipanti</p>
            <img src="../img/match.png" alt="Match">
        </div>
    </header>
    
    <form id="contenitore">
        <h2>Inserisci le informazioni generali dei partecipanti</h2>
        
        <div id="righe">
            <label for="numero">Numero partecipanti</label>
            <input type="number" id="numero" placeholder="8" min="0" max="128">
        </div>

        <table id="ingressi"></table>                             

        <span class="error"></span>
        <button id="myButton">Salva</button>
    </form>
</body>
</html>