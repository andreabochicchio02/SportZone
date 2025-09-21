<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sport Zone - Accesso</title>
    <meta name="viewport" content="width=device-width">
    
    <link rel="icon" type="image/x-icon" href="../img/logo_senza_sfondo.png">
    
    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/accesso.js"></script>
</head>

<body onload="begin()">

    <?php
        /*Se l'utente è già loggato lo reindirizzo*/
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(isset($_SESSION['id'])){
            header("Location: pannello.php");
        }
    ?>

    <section id="container">
        <form id="ingressi">
            <h2>Accesso</h2>

            <input type="text" name="user" id="username" placeholder="Username">
            <span class="error"></span> <!--Usato per gli errori di tutti gli input-->

            <div class="box-pass">
                <input type="password" name="pass" id="password" placeholder="Password">
                
                <button  id="view_pass">
                    <img src="../img/view.png" alt="view_pass"/>
                </button>
            </div>
            <span class="error"></span>

            <span class="error"></span>
            <input type="button" value="Accedi" id="myButton">
        </form>

        <a href="../HTML/registrazione.html">Non hai un account? <span id="reg">Crea un account</span></a>
    </section>  

</body>
</html>