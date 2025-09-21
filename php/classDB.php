<?php

define('DB_CONNECTION_STRING','mysql:host=127.0.0.1;port=3307;dbname=sportzone');
define('DB_USER','root');
define('DB_PASS','');


class cncDB{
    private $pdo;

    public function __construct(){
        try {
            $this->pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASS);
        } 
        catch (PDOException $e) {
            die( $e->getMessage() );
        } 
    }

    public function getPDO(){
        return $this->pdo;
    }

    public function close(){
        $this->pdo = null;
    }

    public function getTorneo(){
        $query =  " SELECT * 
                    FROM Torneo T 
                    WHERE T.Nome =  '{$_SESSION['torneo']}'";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartite(){
        $query =  " SELECT * 
                    FROM partita M
                    INNER JOIN partecipante P ON M.Partecipante1 = P.Id
                    WHERE P.Torneo = '{$_SESSION['torneo']}'";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartecipanti(){
        $query =  " SELECT P.Nome, P.Id
                    FROM partecipante P
                    WHERE P.Torneo = '{$_SESSION['torneo']}'";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTipologia(){
        $query =  " SELECT T.Tipologia
                    FROM Torneo T
                    WHERE T.Nome = '{$_SESSION['torneo']}'";
        $result = $this->pdo->query($query);
        return $result->fetchColumn();
    }

    public function getSport(){
        $query =  " SELECT T.Sport
                    FROM Torneo T
                    WHERE T.Nome = '{$_SESSION['torneo']}'";
        $result = $this->pdo->query($query);
        return $result->fetchColumn();
    }

    public function getPartiteSquadra($partecipante, $turno){
        $query =  " SELECT * 
                    FROM partita P
                    WHERE (P.Partecipante1 = '$partecipante'
                        OR P.Partecipante2 = '$partecipante')
                        AND P.Turno = '$turno';";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartiteTurno($turno){
        $query =  " SELECT *
                    FROM partita M
                        INNER JOIN partecipante P1 ON M.Partecipante1 = P1.Id
                        INNER JOIN partecipante P2 ON M.Partecipante2 = P2.Id
                    WHERE P1.Torneo = '{$_SESSION['torneo']}'
                        AND M.Turno = '$turno';";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartiteTorneo(){
        $query =  " SELECT P1.Nome AS Nome1, P2.Nome AS Nome2, M.Data, M.Luogo, M.Turno, M.Punti1, M.Punti2
                    FROM partita M
                        INNER JOIN partecipante P1 ON M.Partecipante1 = P1.Id
                        INNER JOIN partecipante P2 ON M.Partecipante2 = P2.Id
                    WHERE P1.Torneo = '{$_SESSION['torneo']}'
                    ORDER BY M.Turno, M.Data;";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartiteCronologico(){
        $query =  " SELECT P1.Nome AS Nome1, P2.Nome AS Nome2, M.Data
                    FROM partita M
                        INNER JOIN partecipante P1 ON M.Partecipante1 = P1.Id
                        INNER JOIN partecipante P2 ON M.Partecipante2 = P2.Id
                    WHERE P1.Torneo = '{$_SESSION['torneo']}'
                    ORDER BY M.Data;";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartitePartecipante($partecipante){
        $query =  "SELECT *
                FROM partita P
                    WHERE (P.Partecipante1 = $partecipante
                    OR P.Partecipante2 = $partecipante)
                    AND P.Punti1 IS NOT NULL;";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTornei(){
        $query =  " SELECT T.Nome, T.Sport, T.Luogo, T.DataInizio, T.DataFine 
                    FROM Torneo T
                    WHERE T.Username = '{$_SESSION['id']}'";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getId($partecipante){
        $query =  " SELECT T.Id
                    FROM Partecipante T
                    WHERE T.Torneo = '{$_SESSION['torneo']}'
                        AND T.Nome = '$partecipante';";
        $result = $this->pdo->query($query);
        return $result->fetchColumn();
    }

    public function punteggio($ID, $vinte, $pareggiate){
        $query =  " UPDATE Partecipante P
                    SET P.Vinte = P.Vinte + $vinte, P.Pareggiate = P.Pareggiate + $pareggiate
                    WHERE P.Id = $ID;";
        $result = $this->pdo->query($query);
    }

    public function classifica(){
        $query = "  SELECT P.Nome, P.Vinte, P.Pareggiate
                    FROM Partecipante P
                    WHERE P.Torneo = '{$_SESSION['torneo']}'
                    ORDER BY P.Vinte DESC, P.Pareggiate DESC;";
        $result = $this->pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>