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
        if(!isset($_POST['partecipante1']))
                throw new Exception("Partecipante 1 è campo obbligatorio");
        
        if(!isset($_POST['partecipante2']))
                throw new Exception("Partecipante 2 è campo obbligatorio");

        if ($_POST['partecipante1'] === $_POST['partecipante2'])
            throw new Exception("Partecipante 1 e Partecipante 2 coincidono");

        $partite1 = $connection->getPartiteSquadra($_POST['partecipante1'], $_POST['turno']);
        if(count($partite1) !== 0)
            throw new Exception("Impossibile aggiungere Partecipante1 in questo turno");

        $partite2 = $connection->getPartiteSquadra($_POST['partecipante2'], $_POST['turno']);
        if(count($partite2) !== 0)
            throw new Exception("Impossibile aggiungere Partecipante2 in questo turno");

            
        /*Per i tonei di tipo girone non c'è bisogno di fare questo controllo*/
        $numero = count($connection->getPartiteTurno($_POST['turno']));
        
        if($connection->getTipologia()==="eliminazione2"){
            if($_POST['turno']==0 && $numero>0)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']<2 && $numero>=2)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']<4 && $numero>=4)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']<6 && $numero>=8)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']<8 && $numero>=16)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']<10 && $numero>=32)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']<12 && $numero>=64)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']<14 && $numero>=128)
                    throw new Exception("Troppe partite per questo turno");
                
        } elseif($connection->getTipologia()==="eliminazione1") {
            if($_POST['turno']==0 && $numero>0)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']==1 && $numero>=2)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']==2 && $numero>=4)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']==3 && $numero>=8)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']==4 && $numero>=16)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']==5 && $numero>=32)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']==6 && $numero>=64)
                    throw new Exception("Troppe partite per questo turno");
            elseif($_POST['turno']==7 && $numero>=128)
                    throw new Exception("Troppe partite per questo turno");
        }
    
        
        $data = empty($_POST['data'])? '00/00/0000' : $_POST['data'];
        $ora = empty($_POST['ora'])? '00:00' : $_POST['ora'];

        $data_time = $data . " " . $ora;
        
        
        $query = "INSERT INTO partita (Partecipante1, Partecipante2, Data, Turno, Luogo) 
                        VALUES (:p1, :p2, :data, :turno, :luogo)";

        $statement = $pdo->prepare($query);
        $statement->bindValue(":p1", $_POST['partecipante1']);
        $statement->bindValue(":p2", $_POST['partecipante2']);
        $statement->bindValue(":data", $data_time);
        $statement->bindValue(":turno", $_POST['turno']);
        $statement->bindValue(":luogo", $_POST['luogo']);
        $statement->execute();
        
        $response = [
            'partita'=> true,
            'message' => 'Match creato'
        ];
    
    } catch (PDOException $pdoException) {
        $response = [
                'partita' => false,
                'error' => 'Creazione match fallita',
                'message' => $pdoException->getMessage()
            ];
    } catch (Exception $e) {
        $response = [
            'partita' => false,
            'error' => $e->getMessage(),
            'message' => 'Creazione match fallita'
        ];
    }

    header("Content-type: application/json"); 
    echo json_encode($response);

    $connection->close();
    $pdo = null;
?>

