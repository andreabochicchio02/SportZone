<?php

    /*Controllo che nessuna sessione è stata avviata*/
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "../classDB.php";
    $connection = new cncDB();
    $pdo = $connection->getPDO();

    try {

        /*CONTROLLO GENERALE*/
        $data = ['user', 'pass'];
        if (!isset($_POST['user']) ||  !isset($_POST['pass'])){
            throw new Exception("Tutti i campi sono obbligatori");}
            
        foreach ($data as $input) {
            if($_POST[$input] == ''){
                throw new Exception("Tutti i campi sono obbligatori");
            }
        };


        $query = "  SELECT Password 
                    FROM utente 
                    WHERE Username=:user";

        $statement = $pdo->prepare($query);
        $statement->bindValue(":user", $_POST['user']);
        $statement->execute();


        if ($statement->rowCount() === 0) {
            throw new Exception("Username o password errate");
        } else {
            $hash = $statement->fetch(pdo::FETCH_ASSOC);
            if (!password_verify($_POST['pass'], $hash['Password'])) {
                throw new Exception("Username o password errate");
            }
        }

        $_SESSION['id'] = $_POST['user'];

        $response = [
            'login'=> true,
            'message' => 'Account registrato'
        ];

        } 
        catch (PDOException $pdoException) {
            $response = [
                    'login' => false,
                    'error' => 'Accesso fallito',
                    'message' => $pdoException->getMessage()
                ];
        } catch (Exception $e) {
            $response = [
                'login' => false,
                'error' => $e->getMessage(),
                'message' => 'Accesso fallito'
            ];
        }

        header("Content-type: application/json");
        echo json_encode($response);

        $connection->close();
        $pdo = null;
?>