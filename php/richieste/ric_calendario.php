<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "../classDB.php";

    $connection = new cncDB();
    $pdo = $connection->getPDO();

    try{
        /*Restituisce le partite del torneo nella variabile $_SESSION*/
        if (!isset($_SESSION['torneo'])){
            $response = "Errore ricerca torneo";
        }else{
            $partite = $connection->getPartiteCronologico();
            $response = [
                'ricerca' => true,
                'dati' => $partite
            ];
        }
    } catch (PDOException $pdoException) {
        $response = [
                'ricerca' => false,
                'error' => 'Operazione fallita',
                'message' => $pdoException->getMessage()
            ];
    }

    header("Content-type: application/json");
    echo json_encode($response);

    $connection->close();
    $pdo = null;
?>

