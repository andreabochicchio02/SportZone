
<?php

    require_once "../classDB.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    /*Controllo se ha fatto login*/
    if (!isset($_SESSION['id'])){
        header("Location: ../login.php");
    }

    $connection = new cncDB();
    $pdo = $connection->getPDO();
    
    try{
        /*Controllo che c'è un ingresso*/
        if(empty($_POST['casella'])){
            throw new Exception("Non lasciare righe vuote");
        }

        /*Controlla che ogni sigolo indice è non vuoto*/
        foreach ($_POST['casella'] as $valore) {
            if (empty($valore)) {
                throw new Exception("Non lasciare righe vuote");
            }
        }
    
        foreach($_POST['casella'] as $input){
            $partecipanti = $connection->getPartecipanti();

            if(count($connection->getPartecipanti()) > 128)
                throw new Exception("Superato limite partecipanti");

            foreach($partecipanti as $inseriti){
                if($input === $inseriti['Nome'])
                    throw new Exception("Nome partecipante già usato");
            }

            $query = "INSERT INTO partecipante (Nome, Torneo) VALUES (:nome, :torneo)";

            $statement = $pdo->prepare($query);
            $statement->bindValue(":nome", $input);
            $statement->bindValue(":torneo", $_SESSION['torneo']);
            $statement->execute();
        }

        $response = [
            'partecipanti'=> true,
            'message' => 'Partecipanti inseriti'
        ];
    
    } catch (PDOException $pdoException) {
        $response = [
                'partecipanti' => false,
                'massager' => $pdoException->getMessage(),
                'error' => 'Inserimento partecipanti fallita'
            ];
    } catch (Exception $e) {
        $response = [
            'partecipanti' => false,
            'error' => $e->getMessage(),
            'message' => 'Inserimento partecipanti fallita'
        ];
    }

    header("Content-type: application/json");
    echo json_encode($response);

    $connection->close();
    $pdo = null;
?>

