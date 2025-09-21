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
        
        if(!isset($_POST['punti1']) || !isset($_POST['punti2']))
            throw new Exception("Tutti i campi sono obbligatori");
        
        $sport = $connection->getSport();
        if($sport !== "Calcio" &&  $_POST['punti1'] === $_POST['punti2']){
            throw new Exception("In questo sport non esiste il pareggio");
        }

        $tipo = $connection->getTipologia();
        if($tipo === "eliminazione1" && $_POST['punti1'] === $_POST['punti2']){
            throw new Exception("In questo torneo non esiste il pareggio");
        }
        
        if(($sport === "Pallavolo" || $sport === "Tennis") && $_POST['punti1'] != 3 && $_POST['punti2']!= 3)
            throw new Exception("Errore inserimento punteggio");

        
        $p1 = $connection->getId($_POST['id1']);
        $p2 = $connection->getId($_POST['id2']);
        

        $query = "UPDATE partita P SET P.Punti1=:punti1, P.Punti2=:punti2
                WHERE P.Partecipante1=:p1 AND P.Partecipante2=:p2;";
        $statement = $pdo->prepare($query);
        $statement->bindValue(":punti1", $_POST['punti1']);
        $statement->bindValue(":punti2", $_POST['punti2']);
        $statement->bindValue(":p1", $p1);
        $statement->bindValue(":p2", $p2);
        $statement->execute();


        if($_POST['punti1'] > $_POST['punti2']){
            $connection->punteggio($p1, 1, 0);
        } elseif($_POST['punti1'] < $_POST['punti2']){
            $connection->punteggio($p2, 1, 0);
        } else {
            $connection->punteggio($p1, 0, 1);
            $connection->punteggio($p2, 0, 1);
        }

        
        $response = [
            'risultato'=> true,
            'message' => 'Risultato inserito'
        ];
    
    } catch (PDOException $pdoException) {
        $response = [
                'risultato' => false,
                'error' => 'Inserimento risultato fallito',
                'message' => $pdoException->getMessage()
            ];
    } catch (Exception $e) {
        $response = [
            'risultato' => false,
            'error' => $e->getMessage(),
            'message' => 'Inserimento risultato fallito'
        ];
    }

    header("Content-type: application/json");
    echo json_encode($response);

    $connection->close();
    $pdo = null;
?>

