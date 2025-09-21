
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
        $data = ['user', 'mail', 'pass', 'confirm_pass'];
        if (!isset($_POST['user']) ||  !isset($_POST['mail']) || 
            !isset($_POST['pass']) || !isset($_POST['confirm_pass'])){
            throw new Exception("Tutti i campi sono obbligatori");}


        /*CONTROLLI SU USERNAME */
        $patt_user = '/^[a-zA-Z0-9]{8,}$/';
        if (!preg_match($patt_user, $_POST['user'])) {
            throw new Exception("Almeno 8 caratteri e nessun simbolo speciale");
        }
        /*Nel DB c'è il vincolo di chiave unica*/

        /*CONTROLLI SU EMAIL*/
        $patt_mail = '/^(.+)@([^\.].*)\.([a-z]{2,})$/';
        if (!preg_match($patt_mail, $_POST['mail'])) {
            throw new Exception("Indirizzo email non valido");
        }

        /*CONTROLLI SU PASSWORD*/
        $patt_pass = '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/';
        if (!preg_match($patt_pass, $_POST['pass'])) {
            throw new Exception("Almeno 8 caratteri con maiuscola, minuscola e numero");
        }

        /*CONTROLLI SU RIPETI PASSWORD*/
        if($_POST['pass'] != $_POST['confirm_pass']){
            throw new Exception("Conferma password sbagliata");
        }

        $query = "INSERT INTO utente (Username, Email, Password) VALUES (:user, :email, :pass)";

        $statement = $pdo->prepare($query);
        $statement->bindValue(":user", $_POST['user']);
        $statement->bindValue(":email", $_POST['mail']);
        $statement->bindValue(":pass", password_hash($_POST['pass'], PASSWORD_BCRYPT));
        $statement->execute();

        /*Faccio già il LOGIN*/
        $_SESSION['id'] = $_POST['user'];

        $response = [
            'register'=> true,
            'message' => 'Account registrato'
        ];

    } 
    catch (PDOException $pdoException) {
        $errorCode = $pdoException->getCode();      /*Restituisce codice errore*/
        
        if ($errorCode === "23000") {               /*Violazione regole di password primaria*/
            $response = [
                'register' => false,
                'error' => 'Username già utilizzato',
                'message' => 'Registrazione fallita'
            ];
        } else {
            $response = [
                'register' => false,
                'error' => 'Registrazione fallita',
                'message' => $pdoException->getMessage()
            ];
        }
    } catch (Exception $e) {
        $response = [
            'register' => false,
            'error' => $e->getMessage(),
            'message' => 'Registrazione fallita'
        ];
    }

    header("Content-type: application/json");
    echo json_encode($response);

    $connection->close();
    $pdo = null;
?>