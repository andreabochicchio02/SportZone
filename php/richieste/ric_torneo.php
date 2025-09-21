<?php
    require_once "../classDB.php";

    $connection = new cncDB();
    $pdo = $connection->getPDO();

    try{
        if(empty($_GET['tornei'])){
            throw new Exception("Inserire nome torneo");
        }

        $query = "SELECT * FROM Torneo T WHERE T.Nome = :nome ";

        $statement = $pdo->prepare($query);
        $statement->bindValue(":nome", $_GET['tornei']);
        $statement->execute();

        if($statement->rowCount() === 0){
            throw new Exception("Torneo non trovato");
        }

        $response = [
            'ricerca'=> true,
            'message' => 'Ricerca corretta'
        ];

    } catch (PDOException $pdoException) {
        $response = [
                'ricerca' => false,
                'massage' => 'Ricerca fallita',
                'error' => $pdoException->getMessage()
            ];
    } catch (Exception $e) {
        $response = [
            'ricerca' => false,
            'error' => $e->getMessage(),
            'message' => 'Ricerca fallita'
        ];
    }

    header("Content-type: application/json");
    echo json_encode($response);

    $connection->close();
    $pdo = null;
?>

