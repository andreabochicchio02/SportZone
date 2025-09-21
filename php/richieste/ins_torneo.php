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
        if (empty($_POST['nome']))
            throw new Exception("Nome, Sport e Tipologia sono campi obbligatori");
        if (!isset($_POST['choices']))
            throw new Exception("Nome, Sport e Tipologia sono campi obbligatori");
        if (!isset($_POST['tipo']))
            throw new Exception("Nome, Sport e Tipologia sono campi obbligatori");
 

        $data_inizio = $_POST['data_inizio'];
        $data_fine = $_POST['data_fine'];
        
        if ($data_inizio > $data_fine)
            throw new Exception("Data inizio non può essere dopo Data fine");
    
        $query = "INSERT INTO torneo (Nome, Sport, Luogo, Tipologia, DataInizio, DataFine, Username) 
                    VALUES (:nome, :sport, :luogo, :tipo, :inizio, :fine, :user)";

        $statement = $pdo->prepare($query);
        $statement->bindValue(":nome", $_POST['nome']);
        $statement->bindValue(":sport", $_POST['choices']);
        $statement->bindValue(":luogo", $_POST['luogo']);
        $statement->bindValue(":tipo",$_POST['tipo']);
        $statement->bindValue(":inizio", $_POST['data_inizio']);
        $statement->bindValue(":fine", $_POST['data_fine']);
        $statement->bindValue(":user", $_SESSION['id']);
        $statement->execute();

        $_SESSION['torneo'] = $_POST['nome'];
        
        $response = [
            'torneo'=> true,
            'message' => 'Torneo creato'
        ];
    
    } catch (PDOException $pdoException) {
        $errorCode = $pdoException->getCode();      /*Restituisce codice errore*/
     
        if ($errorCode === "23000") {               /*Violazione regole di password primaria*/
            $response = [
                'torneo' => false,
                'error' => 'Nome torneo già utilizzato',
                'message' => $pdoException->getMessage()
            ];
        } else {
            $response = [
                'torneo' => false,
                'error' => 'Creazione torneo fallita',
                'message' => $pdoException->getMessage()
            ];
        }
    } catch (Exception $e) {
        $response = [
            'torneo' => false,
            'error' => $e->getMessage(),
            'message' => 'Creazione torneo fallita'
        ];
    }

    header("Content-type: application/json");
    echo json_encode($response);

    $connection->close();
    $pdo = null;
?>

