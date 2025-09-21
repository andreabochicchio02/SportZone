<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sport Zone</title>
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/x-icon" href="img/logo_senza_sfondo.png">

    <link rel="stylesheet" href="css/index.css">
    <script src="js/index.js"></script>
</head>

<body onload="begin()">
    <?php
        /*Mi collego al DB trovare l'elenco dei tornei creati*/
        require_once "php/classDB.php";

        $connection = new cncDB();
        $pdo = $connection->getPDO();
    ?>
    
    <!-- BARRA DI NAVIGAZIONE -->
    <header class="hide bar" id="bar_nav">
        <img src="img/insegna_senza_sfondo.png" alt="Logo">

        <!-- In JS creo i pulsanti già presenti nella pagina iniziale-->
    </header>


    <!-- PULSANTI PAGINA INIZIALE -->
    <main>
        <img id="logo" src="img/insegna_senza_sfondo.png" alt="Logo" >

        <h1>Tutto il tuo sport <br> a portata di click</h1>

        <nav>
            <button class="cerca">
                <img src="img/search.png" alt="Cerca">
                Cerca il tuo torneo
            </button>

            <!--inizialmente nascosto-->
            <div class="hide">
                <input type="text" name="torneo" list="tornei1" class="torneo">

                
                <datalist id="tornei1">
                <?php
                    $query =  " SELECT T.Nome
                                FROM Torneo T;";
                    $result = $pdo->query($query);
                    $nomi = $result->fetchAll(PDO::FETCH_ASSOC);

                    foreach($nomi as $opzioni){
                        echo "<option>" . $opzioni['Nome'] . "</option>";
                    }
                ?>
                </datalist>
                
                <button class="invio"> 
                    <img src="img/search.png" alt="Cerca"> 
                </button>

                <button class="chiudi"> 
                    <img src="img/close.png" alt="Cerca"> 
                </button>
            </div>

            <button class="accedi">
                <img src="img/edit.png" alt="Crea">
                Crea il tuo torneo
            </button>
        </nav>

        <button id="scopri">
                SCOPRI DI PIÙ <br>
                <img src="img/down.png" alt="Scopri">
        </button>

    </main>

    <!-- SCOPRI DI PIÙ-->
    <article>
        <h2>
            Sei un organizzatore? <br> Organizza e gestisci i tuoi tornei sportivi 
            in modo semplice e intuitivo attraverso la nostra piattaforma
        </h2>
        <img src="img/organizza.png" alt="Organizza">
    </article>


    <article>
        <h2>
        Sarai guidato passo dopo passo nell'organizzazione del torneo, dall'aggiunta dei partecipanti alla pianificazione dei match
        </h2>
        <img src="img/form.png" alt="Organizza">
    </article>


    <article>
        <h2>
            Sei un partecipante? <br>
            Sul nostro sito puoi trovare sia i risultati delle tue gare che il calendario, il tutto in un unico luogo comodo e accessibile
        </h2>
        <img src="img/controllo.png" alt="Controllo">
    </article>


    <article>
        <h2>
            La nostra piattaforma ti offre anche la possibilit&agrave; di tenere d'occhio le prestazioni degli altri partecipanti. 
            Questo ti consente di preparanti al meglio
        </h2>
        <img src="img/sfida.png" alt="Sfida">
    </article>

    <footer>
        <a href="HTML/manuale.html">MANUALE UTENTE</a>
    </footer>


    <?php
        $connection->close();
        $pdo = null;
    ?>
    
</body>
</html>